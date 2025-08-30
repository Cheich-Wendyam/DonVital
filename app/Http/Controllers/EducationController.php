<?php

namespace App\Http\Controllers;

use App\Models\EducationalContent;
use App\Models\UserContentCompletion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducationController extends Controller
{
    /**
     * Récupère les contenus éducatifs avec filtres
     */
    public function getContents(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category' => ['nullable', Rule::in(['discovery', 'preparation', 'quiz', 'testimonials', 'progress'])],
            'type' => ['nullable', Rule::in(['infographic', 'video', 'quiz', 'checklist', 'article', 'testimony'])],
            'difficulty' => 'nullable|integer|min:1|max:5',
            'sort' => 'nullable|in:newest,popular,difficulty',
            'search' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $query = EducationalContent::query()->where('is_active', true);

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->has('difficulty') && $request->difficulty > 0) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        switch ($request->sort) {
            case 'popular':
                $query->withCount('completions')->orderBy('completions_count', 'desc');
                break;
            case 'difficulty':
                $query->orderBy('difficulty', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $contents = $query->with(['completions' => function ($q) {
            $q->where('user_id', Auth::id());
        }])
            ->withCount('completions')
            ->paginate(12);

        // Ajout de l'URL publique du média
        $contents->getCollection()->transform(function ($content) {
            if ($content->media_path) {
                $content->media_url = Storage::url($content->media_path);
            }
            return $content;
        });

        return response()->json([
            'success' => true,
            'contents' => [
                'data' => $contents->items(),
                'meta' => [
                    'total' => $contents->total(),
                    'per_page' => $contents->perPage(),
                    'current_page' => $contents->currentPage(),
                    'last_page' => $contents->lastPage(),
                ]
            ]
        ]);
    }

    /**
     * Détail d'un contenu
     */
    public function getContentDetail($id)
    {
        $content = EducationalContent::with(['completions' => function ($query) {
            $query->where('user_id', Auth::id());
        }])
            ->withCount('completions')
            ->findOrFail($id);

        // Correction de l'URL publique
        if ($content->media_path) {
            $content->media_url = Storage::url($content->media_path);
        }

        $totalUsers = User::count();
        $completionRate = $totalUsers > 0
            ? ($content->completions_count / $totalUsers) * 100
            : 0;

        $content->completion_rate = round($completionRate, 2);

        return response()->json(['success' => true, 'content' => $content]);
    }

    /**
     * Marque un contenu comme complété par l'utilisateur
     */
    public function completeContent(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100'
        ]);

        $content = EducationalContent::findOrFail($id);
        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà complété ce contenu
        $existingCompletion = UserContentCompletion::where([
            'user_id' => $user->id,
            'content_id' => $id
        ])->first();

        if ($existingCompletion) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà complété ce contenu'
            ], 409);
        }

        // Créer la complétion
        $completion = UserContentCompletion::create([
            'user_id' => $user->id,
            'content_id' => $id,
            'score' => $request->score,
            'completed_at' => now()
        ]);

        // Attribution des points
        $pointsEarned = $content->points;

        // Bonus pour les bons scores
        if ($request->score >= 90) {
            $pointsEarned += round($content->points * 0.3); // Bonus 30%
        } elseif ($request->score >= 80) {
            $pointsEarned += round($content->points * 0.2); // Bonus 20%
        } elseif ($request->score >= 70) {
            $pointsEarned += round($content->points * 0.1); // Bonus 10%
        }

        // Mettre à jour les points de l'utilisateur
        $user->increment('points', $pointsEarned);

        // Vérifier les réalisations débloquées
        $this->checkAchievements($user);

        return response()->json([
            'success' => true,
            'points_earned' => $pointsEarned,
            'total_points' => $user->points,
            'completion' => $completion
        ]);
    }

    /**
     * Récupère la progression de l'utilisateur
     */
    public function getUserProgress()
    {
        $user = Auth::user();

        $stats = [
            'total_contents' => EducationalContent::count(),
            'completed_contents' => $user->contentCompletions()->count(),
            'total_points' => $user->points,
            'education_level' => $this->calculateEducationLevel($user),
            'categories' => []
        ];

        // Progression par catégorie
        $categories = ['discovery', 'preparation', 'quiz', 'testimonials'];
        foreach ($categories as $category) {
            $total = EducationalContent::where('category', $category)->count();
            $completed = $user->contentCompletions()
                ->whereHas('content', function($q) use ($category) {
                    $q->where('category', $category);
                })
                ->count();

            $stats['categories'][$category] = [
                'completed' => $completed,
                'total' => $total,
                'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0
            ];
        }

        // Contenus récemment complétés
        $stats['recent_completions'] = $user->contentCompletions()
            ->with('content')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'progress' => $stats
        ]);
    }

    /**
     * Calcule le niveau d'éducation de l'utilisateur
     */
    private function calculateEducationLevel(User $user)
    {
        $completed = $user->contentCompletions()->count();

        if ($completed >= 20) return 5; // Expert
        if ($completed >= 15) return 4; // Avancé
        if ($completed >= 10) return 3; // Intermédiaire
        if ($completed >= 5) return 2;  // Débutant
        return 1;                       // Novice
    }

    /**
     * Vérifie et attribue les réalisations
     */
    private function checkAchievements(User $user)
    {
        $completedCount = $user->contentCompletions()->count();

        // Réalisation "Apprenti"
        if ($completedCount >= 3 && !$user->achievements()->where('name', 'Apprenti')->exists()) {
            $user->achievements()->attach(1, ['unlocked_at' => now()]);
            $user->increment('points', 50);
        }

        // Réalisation "Expert en éducation"
        if ($completedCount >= 10 && !$user->achievements()->where('name', 'Expert en éducation')->exists()) {
            $user->achievements()->attach(2, ['unlocked_at' => now()]);
            $user->increment('points', 200);
        }

        // Réalisation "Maître des quiz"
        $quizCount = $user->contentCompletions()
            ->whereHas('content', function($q) {
                $q->where('type', 'quiz');
            })
            ->count();

        if ($quizCount >= 5 && !$user->achievements()->where('name', 'Maître des quiz')->exists()) {
            $user->achievements()->attach(3, ['unlocked_at' => now()]);
            $user->increment('points', 150);
        }
    }

    /**
     * Récupère les contenus recommandés pour l'utilisateur
     */
    public function getRecommendedContents()
    {
        $user = Auth::user();

        // 1. Contenus non complétés dans les catégories que l'utilisateur a commencées
        $startedCategories = $user->contentCompletions()
            ->with('content')
            ->get()
            ->pluck('content.category')
            ->unique();

        $recommended = EducationalContent::whereIn('category', $startedCategories)
            ->whereNotIn('id', $user->contentCompletions->pluck('content_id'))
            ->orderBy('difficulty', 'asc')
            ->limit(5)
            ->get();

        // 2. Si pas assez, ajouter des contenus populaires
        if ($recommended->count() < 5) {
            $popular = EducationalContent::withCount('completions')
                ->whereNotIn('id', $user->contentCompletions->pluck('content_id'))
                ->orderBy('completions_count', 'desc')
                ->limit(5 - $recommended->count())
                ->get();

            $recommended = $recommended->merge($popular);
        }

        // 3. Si toujours pas assez, ajouter des contenus aléatoires
        if ($recommended->count() < 5) {
            $random = EducationalContent::inRandomOrder()
                ->whereNotIn('id', $user->contentCompletions->pluck('content_id'))
                ->limit(5 - $recommended->count())
                ->get();

            $recommended = $recommended->merge($random);
        }

        return response()->json([
            'success' => true,
            'recommended_contents' => $recommended
        ]);
    }

    public function statistics()
    {
        // Logique pour récupérer les statistiques
        $stats = [
            'total_contents' => EducationalContent::count(),
            'total_quizzes' => EducationalContent::where('type', 'quiz')->count(),
            'total_completions' => UserContentCompletion::count(),
            'active_users' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
        ];

        // Récupérer les données pour les graphiques
        $activity = [
            'dates' => [],
            'completions' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $activity['dates'][] = $date;
            $activity['completions'][] = UserContentCompletion::whereDate('completed_at', $date)->count();
        }

        $categories = [
            'names' => ['Découverte', 'Préparation', 'Quiz', 'Témoignages'],
            'counts' => [
                EducationalContent::where('category', 'discovery')->count(),
                EducationalContent::where('category', 'preparation')->count(),
                EducationalContent::where('category', 'quiz')->count(),
                EducationalContent::where('category', 'testimonials')->count(),
            ]
        ];

        return view('education.statistics', compact('stats', 'activity', 'categories'));
    }

    public function dashboard()
    {
        // Statistiques pour le tableau de bord
        $stats = [
            'total_contents' => EducationalContent::count(),
            'total_quizzes' => EducationalContent::where('type', 'quiz')->count(),
            'total_completions' => UserContentCompletion::count(),
            'active_users' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
        ];

        // Activité récente (7 derniers jours)
        $activity = [
            'dates' => [],
            'completions' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $activity['dates'][] = $date;
            $activity['completions'][] = UserContentCompletion::whereDate('completed_at', $date)->count();
        }

        // Répartition par catégorie
        $categories = [
            'names' => ['Découverte', 'Préparation', 'Quiz', 'Témoignages'],
            'counts' => [
                EducationalContent::where('category', 'discovery')->count(),
                EducationalContent::where('category', 'preparation')->count(),
                EducationalContent::where('category', 'quiz')->count(),
                EducationalContent::where('category', 'testimonials')->count(),
            ]
        ];

        return view('education.dashboard', compact('stats', 'activity', 'categories'));
    }

    public function create()
    {
        return view('education.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:infographic,video,quiz,article,checklist,testimony',
            'category' => 'required|in:discovery,preparation,quiz,testimonials',
            'difficulty' => 'nullable|integer|min:1|max:5',
            'points' => 'nullable|integer|min:0',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,mp4,mov,avi,webm,ogg|max:102400',
            'media_url' => 'nullable|url',
            'quiz_data' => 'nullable|json',
        ]);

        // Gestion du fichier média
        if ($request->hasFile('media')) {
            try {
                $file = $request->file('media');
                $extension = $file->getClientOriginalExtension();

                $folder = ($validated['type'] === 'video') ? 'videos' : 'images';
                $storagePath = "education/$folder";

                $fileName = Str::uuid() . '.' . $extension;
                $path = $file->storeAs("public/$storagePath", $fileName);

                $validated['media_path'] = "$storagePath/$fileName";
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Erreur upload: ' . $e->getMessage());
            }
        }

        $validated['is_active'] = true;
        EducationalContent::create($validated);

        return redirect()->route('education.index')->with('success', 'Contenu créé avec succès.');
    }

    public function index()
    {
        $contents = EducationalContent::paginate(10);
        return view('education.index', compact('contents'));
    }

    public function show($id)
    {
        $content = EducationalContent::findOrFail($id);

        // Charger les complétions de ce contenu avec l'utilisateur lié
        $completions = UserContentCompletion::with('user')
            ->where('content_id', $id)
            ->orderByDesc('completed_at')
            ->paginate(10);

        // Ajouter l'URL du média
        if ($content->media_path) {
            $content->media_url = Storage::url($content->media_path);
        }

        return view('education.show', compact('content', 'completions'));
    }

    public function edit($id)
    {
        $content = EducationalContent::findOrFail($id);

        // Ajouter l'URL du média
        if ($content->media_path) {
            $content->media_url = Storage::url($content->media_path);
        }

        return view('education.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = EducationalContent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:infographic,video,quiz,article,checklist,testimony',
            'category' => 'required|in:discovery,preparation,quiz,testimonials',
            'difficulty' => 'nullable|integer|min:1|max:5',
            'points' => 'nullable|integer|min:0',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,mp4,mov,avi,webm|max:20480',
            'media_url' => 'nullable|url',
            'quiz_data' => 'nullable|json',
            'remove_media' => 'nullable|boolean',
        ]);

        // Supprimer le média si demandé
        if ($request->has('remove_media') && $request->remove_media) {
            if ($content->media_path) {
                Storage::delete("public/" . $content->media_path);
                $validated['media_path'] = null;
            }
        }

        // Gestion du nouveau fichier média
        if ($request->hasFile('media')) {
            // Supprimer l'ancien média s'il existe
            if ($content->media_path) {
                Storage::delete("public/" . $content->media_path);
            }

            // Générer un nom de fichier unique
            $fileName = Str::uuid() . '.' . $request->file('media')->getClientOriginalExtension();

            // Déterminer le dossier en fonction du type
            $folder = ($validated['type'] === 'video') ? 'videos' : 'images';

            // Stocker le fichier
            $path = $request->file('media')->storeAs("public/education/$folder", $fileName);

            // Enregistrer le chemin relatif
            $validated['media_path'] = "education/$folder/$fileName";
        }

        $content->update($validated);

        return redirect()->route('education.show', $content->id)->with('success', 'Contenu mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $content = EducationalContent::findOrFail($id);

        // Supprimer le média associé s'il existe
        if ($content->media_path) {
            Storage::delete("public/" . $content->media_path);
        }

        $content->delete();

        return redirect()->route('education.index')->with('success', 'Contenu supprimé avec succès.');
    }

    // Méthode spécifique pour l'API
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:infographic,video,quiz,article,checklist,testimony',
            'category' => 'required|in:discovery,preparation,quiz,testimonials',
            'difficulty' => 'nullable|integer|min:1|max:5',
            'points' => 'nullable|integer|min:0',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,mp4,mov,avi,webm|max:20480',
            'media_url' => 'nullable|url',
            'quiz_data' => 'nullable|json',
        ]);

        // Gestion du média
        if ($request->hasFile('media')) {
            // Générer un nom de fichier unique
            $fileName = Str::uuid() . '.' . $request->file('media')->getClientOriginalExtension();

            // Déterminer le dossier en fonction du type
            $folder = ($validated['type'] === 'video') ? 'videos' : 'images';

            // Stocker le fichier
            $path = $request->file('media')->storeAs("public/education/$folder", $fileName);

            // Enregistrer le chemin relatif
            $validated['media_path'] = "education/$folder/$fileName";
        }

        $validated['is_active'] = true;
        $content = EducationalContent::create($validated);

        // Ajouter l'URL du média
        if ($content->media_path) {
            $content->media_url = Storage::url($content->media_path);
        }

        return response()->json([
            'success' => true,
            'content' => $content
        ], 201);
    }

    public function getPublicContents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => ['nullable', Rule::in(['discovery', 'preparation', 'quiz', 'testimonials', 'progress'])],
                'type' => ['nullable', Rule::in(['infographic', 'video', 'quiz', 'checklist', 'article', 'testimony'])],
                'difficulty' => 'nullable|integer|min:1|max:5',
                'sort' => 'nullable|in:newest,popular,difficulty',
                'search' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $query = EducationalContent::query()->where('is_active', true);

            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }
            if ($request->has('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }
            if ($request->has('difficulty') && $request->difficulty > 0) {
                $query->where('difficulty', $request->difficulty);
            }
            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            switch ($request->sort) {
                case 'popular':
                    $query->withCount('completions')->orderBy('completions_count', 'desc');
                    break;
                case 'difficulty':
                    $query->orderBy('difficulty', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $contents = $query->withCount('completions')->paginate(12);

            $contents->getCollection()->transform(function ($content) {
                if ($content->media_path) {
                    $content->media_url = Storage::url($content->media_path);
                }
                return $content;
            });

            return response()->json([
                'success' => true,
                'contents' => [
                    'data' => $contents->items(),
                    'meta' => [
                        'total' => $contents->total(),
                        'per_page' => $contents->perPage(),
                        'current_page' => $contents->currentPage(),
                        'last_page' => $contents->lastPage(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Erreur getPublicContents : " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }
    
}
