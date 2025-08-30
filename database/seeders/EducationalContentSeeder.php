<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationalContent;

class EducationalContentSeeder extends Seeder
{
    public function run(): void
    {
        EducationalContent::create([
            'title' => 'Introduction au don de sang',
            'description' => 'Découvrez l’importance du don de sang dans notre société.',
            'type' => 'video',
            'category' => 'discovery',
            'difficulty' => 2,
            'points' => 20,
            'media_path' => 'videos/don_intro.mp4',
            'is_active' => true,
        ]);

        EducationalContent::create([
            'title' => 'Checklist avant un don',
            'description' => 'Les étapes essentielles à suivre avant de donner son sang.',
            'type' => 'checklist',
            'category' => 'preparation',
            'difficulty' => 1,
            'points' => 15,
            'media_path' => null,
            'is_active' => true,
        ]);

        EducationalContent::create([
            'title' => 'Testez vos connaissances',
            'description' => 'Un quiz rapide pour évaluer vos connaissances sur le don de sang.',
            'type' => 'quiz',
            'category' => 'quiz',
            'difficulty' => 3,
            'points' => 30,
            'quiz_data' => json_encode([
                'questions' => [
                    [
                        'question' => 'À quelle fréquence peut-on donner son sang ?',
                        'options' => ['Tous les mois', 'Tous les 2 mois', 'Tous les 6 mois'],
                        'answer' => 1
                    ]
                ]
            ]),
            'is_active' => true,
        ]);
    }
}
