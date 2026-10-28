<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = ['curso_id', 'numero_ciclo', 'ano', 'numero_grupo', 'abierto'];

    protected $casts = [
        'numero_ciclo' => 'integer',
        'ano' => 'integer',
        'numero_grupo' => 'integer',
        'abierto' => 'boolean',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(Docente::class, 'docente_grupo');
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_matriculados')
            ->withPivot('nota')
            ->withTimestamps();
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(EstudianteMatriculado::class);
    }
}
