<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * PowerSyncServiceProvider.
 *
 * Placeholder para la sincronización bidireccional entre SQLite (local, offline-first)
 * y PostgreSQL/Supabase (nube), según la sección 3.1 del Master Blueprint V2.
 *
 * Arquitectura prevista:
 *  - SQLite local (en dispositivo): fuente de verdad offline.
 *  - PostgreSQL (Supabase): réplica en la nube con sync en tiempo real.
 *  - Estrategia de conflictos: "last-write-wins" con timestamp de servidor.
 *  - Cola de sincronización: acciones offline encoladas y procesadas en reconnect.
 */
class PowerSyncServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * TODO: Conectar con el SDK de PowerSync (https://docs.powersync.com/client-sdk-references/react-native)
     *       o la implementación Laravel equivalente.
     */
    public function boot(): void
    {
        // TODO (Phase 1): Registrar event listeners para detectar cambios en modelos
        // con SoftDeletes y encolarlos para sincronización.
        //
        // Ejemplo:
        // Branch::observe(SyncObserver::class);
        // User::observe(SyncObserver::class);

        // TODO (Phase 2): Publicar y cargar rutas de webhook para recibir cambios de Supabase.
        // $this->loadRoutesFrom(__DIR__.'/../../routes/powersync.php');

        // TODO (Phase 3): Configurar el canal de broadcasting para cambios en tiempo real.
        // Broadcast::channel('sync.{tenant}', function ($user, $tenantId) {
        //     return $user->branch_id === $tenantId;
        // });
    }

    /**
     * Register any application services.
     *
     * TODO: Vincular interfaces con implementaciones concretas.
     */
    public function register(): void
    {
        // TODO: Registrar el SyncManager singleton cuando esté implementado.
        // $this->app->singleton(SyncManager::class, function ($app) {
        //     return new SyncManager(
        //         localDsn: config('database.connections.sqlite'),
        //         remoteDsn: config('database.connections.pgsql'),
        //     );
        // });

        // TODO: Cargar configuración de PowerSync.
        // $this->mergeConfigFrom(__DIR__.'/../../config/powersync.php', 'powersync');
    }
}
