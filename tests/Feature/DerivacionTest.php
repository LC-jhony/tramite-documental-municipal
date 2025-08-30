<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Derivacion;
use App\Models\Tramite;
use App\Models\TypeDocument;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DerivacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_derivar_tramite_de_su_area()
    {
        // Crear áreas
        $areaOrigen = Area::factory()->create(['name' => 'Área Origen']);
        $areaDestino = Area::factory()->create(['name' => 'Área Destino']);
        
        // Crear usuario en área origen
        $usuario = User::factory()->create(['area_id' => $areaOrigen->id]);
        
        // Crear tipo de documento
        $tipoDocumento = TypeDocument::factory()->create();
        
        // Crear trámite en área origen
        $tramite = Tramite::factory()->create([
            'area_oreigen_id' => $areaOrigen->id,
            'document_type_id' => $tipoDocumento->id,
        ]);

        // Verificar que el usuario puede derivar el trámite
        $this->assertTrue($tramite->puedeSerDerivadoPor($usuario));

        // Crear derivación
        $derivacion = Derivacion::create([
            'tramite_id' => $tramite->id,
            'area_origen_id' => $areaOrigen->id,
            'area_destino_id' => $areaDestino->id,
            'user_id' => $usuario->id,
            'motivo' => 'Derivación de prueba',
            'fecha_derivacion' => now(),
            'estado' => 'pendiente',
        ]);

        // Verificar que la derivación se creó correctamente
        $this->assertDatabaseHas('derivacions', [
            'tramite_id' => $tramite->id,
            'area_origen_id' => $areaOrigen->id,
            'area_destino_id' => $areaDestino->id,
        ]);

        // Verificar que el área actual del trámite cambió
        $this->assertEquals($areaDestino->id, $tramite->fresh()->getAreaActual()->id);
    }

    public function test_se_crean_notificaciones_al_derivar()
    {
        // Crear áreas
        $areaOrigen = Area::factory()->create();
        $areaDestino = Area::factory()->create();
        
        // Crear usuarios en área destino
        $usuarioDestino1 = User::factory()->create(['area_id' => $areaDestino->id]);
        $usuarioDestino2 = User::factory()->create(['area_id' => $areaDestino->id]);
        
        // Crear usuario en área origen
        $usuarioOrigen = User::factory()->create(['area_id' => $areaOrigen->id]);
        
        // Crear trámite
        $tramite = Tramite::factory()->create([
            'area_oreigen_id' => $areaOrigen->id,
            'document_type_id' => TypeDocument::factory()->create()->id,
        ]);

        // Crear derivación
        $derivacion = Derivacion::create([
            'tramite_id' => $tramite->id,
            'area_origen_id' => $areaOrigen->id,
            'area_destino_id' => $areaDestino->id,
            'user_id' => $usuarioOrigen->id,
            'motivo' => 'Derivación de prueba',
            'fecha_derivacion' => now(),
            'estado' => 'pendiente',
        ]);

        // Verificar que se crearon notificaciones para los usuarios del área destino
        $this->assertEquals(2, Notificacion::where('tramite_id', $tramite->id)->count());
        
        $this->assertDatabaseHas('notificacions', [
            'user_id' => $usuarioDestino1->id,
            'tramite_id' => $tramite->id,
            'tipo' => 'derivacion',
        ]);
        
        $this->assertDatabaseHas('notificacions', [
            'user_id' => $usuarioDestino2->id,
            'tramite_id' => $tramite->id,
            'tipo' => 'derivacion',
        ]);
    }

    public function test_scope_visible_para_area_funciona_correctamente()
    {
        // Crear áreas
        $area1 = Area::factory()->create();
        $area2 = Area::factory()->create();
        $area3 = Area::factory()->create();
        
        // Crear trámites
        $tramite1 = Tramite::factory()->create(['area_oreigen_id' => $area1->id]);
        $tramite2 = Tramite::factory()->create(['area_oreigen_id' => $area2->id]);
        $tramite3 = Tramite::factory()->create(['area_oreigen_id' => $area3->id]);
        
        // Derivar tramite2 a area1
        Derivacion::create([
            'tramite_id' => $tramite2->id,
            'area_origen_id' => $area2->id,
            'area_destino_id' => $area1->id,
            'user_id' => User::factory()->create()->id,
            'motivo' => 'Test',
            'fecha_derivacion' => now(),
        ]);

        // Verificar que area1 puede ver tramite1 (origen) y tramite2 (derivado)
        $tramitesVisibles = Tramite::visibleParaArea($area1->id)->get();
        $this->assertCount(2, $tramitesVisibles);
        $this->assertTrue($tramitesVisibles->contains($tramite1));
        $this->assertTrue($tramitesVisibles->contains($tramite2));
        $this->assertFalse($tramitesVisibles->contains($tramite3));
    }
}
