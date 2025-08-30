<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RewardController extends Controller
{
    // Utilitaire pour corriger l'URL d'image
    private function formatImageUrl(?string $path): ?string
    {
        if (!$path) return null;

        $cleanPath = ltrim(str_replace('storage/', '', $path), '/');
        return asset('storage/' . $cleanPath);
    }

    // Récupérer toutes les récompenses disponibles avec état de réclamation
    public function getRewards()
    {
        $rewards = Reward::where('is_active', true)->get();

        if (Auth::check()) {
            $claimedRewardIds = Auth::user()->rewards->pluck('id')->toArray();

            $rewards->each(function ($reward) use ($claimedRewardIds) {
                $reward->is_claimed = in_array($reward->id, $claimedRewardIds);
                $reward->image_url = $this->formatImageUrl($reward->image_url);
            });
        } else {
            $rewards->each(function ($reward) {
                $reward->image_url = $this->formatImageUrl($reward->image_url);
            });
        }

        return response()->json($rewards);
    }

    // Réclamer une récompense

    public function claimReward($id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $reward = Reward::findOrFail($id);

            if ($user->rewards()->where('reward_id', $id)->exists()) {
                return response()->json(['message' => 'Vous avez déjà réclamé cette récompense'], 400);
            }

            if ($user->points < $reward->cost) {
                return response()->json(['message' => 'Points insuffisants'], 400);
            }

            if ($user->level < $reward->required_level) {
                return response()->json(['message' => 'Niveau insuffisant'], 400);
            }

            $user->points -= $reward->cost;
            $user->save();

            $user->rewards()->attach($reward->id, ['claimed_at' => now()]);

            // ✅ Ajout du log
            Log::info('Récompense réclamée', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'reward_id' => $reward->id,
                'reward_name' => $reward->name,
                'remaining_points' => $user->points,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Récompense réclamée avec succès',
                'remaining_points' => $user->points,
                'user_level' => $user->level
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la réclamation de récompense', [
                'user_id' => Auth::id(),
                'reward_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    // Créer une récompense via API
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|integer|min:1',
            'required_level' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $reward = Reward::create([
            'name' => $request->name,
            'description' => $request->description,
            'cost' => $request->cost,
            'required_level' => $request->required_level,
            'image_url' => $imagePath,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('rewards.index')->with('success', 'Récompense créée avec succès');

    }

    // Interface admin : liste des récompenses
    public function index()
    {
        $rewards = Reward::all();
        return view('rewards.index', compact('rewards'));
    }

    // Interface admin : formulaire de création
    public function create()
    {
        return view('rewards.create');
    }

    // Interface admin : enregistrement
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|integer|min:1',
            'required_level' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Reward::create([
            'name' => $request->name,
            'description' => $request->description,
            'cost' => $request->cost,
            'required_level' => $request->required_level,
            'image_url' => $imagePath,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('rewards.index')->with('success', 'Récompense créée avec succès');
    }

    // Interface admin : formulaire édition
    public function edit(Reward $reward)
    {
        return view('rewards.edit', compact('reward'));
    }

    // Interface admin : mise à jour
    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|integer|min:0',
            'required_level' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'sometimes|boolean'
        ]);

        $data = $request->only(['name', 'description', 'cost', 'required_level', 'is_active']);

        if ($request->hasFile('image')) {
            if ($reward->image_url) {
                Storage::disk('public')->delete($reward->image_url);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $data['image_url'] = $imagePath;
        }

        $reward->update($data);

        return redirect()->route('rewards.index')->with('success', 'Récompense mise à jour avec succès');
    }

    // Interface admin : suppression
    public function destroy(Reward $reward)
    {
        if ($reward->image_url) {
            Storage::disk('public')->delete($reward->image_url);
        }

        $reward->users()->detach();
        $reward->delete();

        return redirect()->route('rewards.index')->with('success', 'Récompense supprimée avec succès');
    }

    // Activer/Désactiver
    public function toggleActive($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->is_active = !$reward->is_active;
        $reward->save();

        return response()->json([
            'message' => 'Statut modifié',
            'is_active' => $reward->is_active
        ]);
    }
            public function toggleStatus(Reward $reward)
        {
            $reward->is_active = !$reward->is_active;
            $reward->save();

            return back()->with('success', 'Statut de la récompense mis à jour avec succès.');
        }

}
