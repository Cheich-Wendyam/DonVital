<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Events\DonConfirmed;

class Don extends Model
{
    use HasFactory;

    protected $table = 'dons';

    protected $fillable = [
        'user_id',
        'annonce_id',
        'etat', // Valeurs possibles: 'en attente', 'confirmé', 'annulé'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function annonce(): BelongsTo
    {
        return $this->belongsTo(Annonce::class);
    }

    /**
     * Confirmer un don
     *
     * Cette méthode change l'état du don en "confirmé"
     * et attribue les points de récompense à l'utilisateur
     */
    public function confirm()
    {
        DB::transaction(function () {
            try {
                // Vérifier que le don n'est pas déjà confirmé
                if ($this->etat === 'confirmé') {
                    throw new \Exception('Ce don est déjà confirmé');
                }

                // Mettre à jour l'état
                $this->etat = 'confirmé';
                $this->save();

                // Attribuer les points de récompense
                $user = $this->user;
                $user->points += $this->calculatePoints();

                // Mettre à jour le niveau de l'utilisateur
                $user->level = $this->calculateUserLevel($user);
                $user->save();

                // Déclencher l'événement de confirmation
                event(new DonConfirmed($this));

            } catch (\Exception $e) {
                Log::error("Erreur de confirmation du don: " . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Annuler un don
     *
     * Cette méthode change l'état du don en "annulé"
     * et retire les points si le don était confirmé
     */
    public function cancel()
    {
        DB::transaction(function () {
            try {
                // Si le don était confirmé, retirer les points
                if ($this->etat === 'confirmé') {
                    $user = $this->user;
                    $user->points -= $this->calculatePoints();

                    // Recalculer le niveau
                    $user->level = $this->calculateUserLevel($user);
                    $user->save();
                }

                // Mettre à jour l'état
                $this->etat = 'annulé';
                $this->save();

            } catch (\Exception $e) {
                Log::error("Erreur d'annulation du don: " . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Calculer les points attribués pour ce don
     *
     * @return int Nombre de points (10 par défaut)
     */
    protected function calculatePoints(): int
    {
        // Points de base
        $points = 10;

        // Bonus pour groupe sanguin rare
        $bloodGroup = $this->user->blood_group;
        if (in_array($bloodGroup, ['AB-', 'B-', 'A-', 'O-'])) {
            $points += 5;
        }

        return $points;
    }

    /**
     * Calculer le niveau de l'utilisateur
     *
     * @param User $user
     * @return int Niveau calculé
     */
    protected function calculateUserLevel(User $user): int
    {
        // 1 niveau tous les 5 dons confirmés
        $confirmedDons = $user->dons()->where('etat', 'confirmé')->count();
        return max(1, floor($confirmedDons / 5) + 1);
    }

    /**
     * Scope pour les dons confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->where('etat', 'confirmé');
    }

    /**
     * Scope pour les dons en attente
     */
    public function scopePending($query)
    {
        return $query->where('etat', 'en attente');
    }

    /**
     * Scope pour les dons annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('etat', 'annulé');
    }
}
