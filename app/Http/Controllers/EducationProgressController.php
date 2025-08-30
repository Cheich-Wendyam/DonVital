<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducationalContent;
use App\Models\UserContentCompletion;

class EducationProgressController extends Controller
{
    /**
     * Récupérer la progression de l’utilisateur connecté
     */
    public function getProgress()
    {
        $user = Auth::user();

        $completions = $user->contentCompletions()->with('content')->get();

        return response()->json([
            'success' => true,
            'progress' => $completions
        ]);
    }

    /**
     * Marquer un contenu comme complété
     */
    public function markCompleted(Request $request, $contentId)
    {
        $user = Auth::user();

        $content = EducationalContent::findOrFail($contentId);

        // Vérifie si déjà complété
        $completion = UserContentCompletion::firstOrCreate(
            [
                'user_id' => $user->id,
                'content_id' => $content->id,
            ],
            [
                'score' => $request->score ?? 0,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Contenu marqué comme complété',
            'completion' => $completion
        ]);
    }
}
