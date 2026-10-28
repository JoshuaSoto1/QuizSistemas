<?php

use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Grupo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('un grupo contiene toda su información asociada', function () {
    $curso = Curso::create(['sigla' => 'IF6100', 'nombre_curso' => 'Análisis y Diseño de Sistemas', 'creditos' => 4]);

    $docente1 = Docente::create(['cod_empleado' => 1001, 'nombre_empleado' => 'Alonso', 'apellidos_empleado' => 'Chavarría Cubero']);
    $docente2 = Docente::create(['cod_empleado' => 1002, 'nombre_empleado' => 'Juan', 'apellidos_empleado' => 'Mora Hernandez']);

    $grupo = Grupo::create([
        'curso_id' => $curso->id,
        'numero_ciclo' => 2,
        'ano' => 2026,
        'numero_grupo' => 1,
        'abierto' => true,
    ]);
    $grupo->docentes()->attach([$docente1->id, $docente2->id]);

    $estudiante = Estudiante::create([
        'carne' => 'C4K209',
        'nombre' => 'Joshua',
        'apellidos' => 'Soto Hernandez',
        'docente_id' => $docente1->id,
    ]);
    $grupo->estudiantes()->attach($estudiante->id, ['nota' => 85.5]);

    $grupo = Grupo::with(['curso', 'docentes', 'estudiantes.consejero'])->find($grupo->id);

    expect($grupo->numero_ciclo)->toBe(2)
        ->and($grupo->ano)->toBe(2026)
        ->and($grupo->numero_grupo)->toBe(1)
        ->and($grupo->abierto)->toBeTrue()
        ->and($grupo->curso->sigla)->toBe('IF6100')
        ->and($grupo->docentes)->toHaveCount(2)
        ->and($grupo->estudiantes)->toHaveCount(1)
        ->and($grupo->estudiantes->first()->carne)->toBe('C4K209')
        ->and($grupo->estudiantes->first()->consejero->cod_empleado)->toEqual(1001)
        ->and($grupo->estudiantes->first()->pivot->nota)->toEqual(85.5);
});
