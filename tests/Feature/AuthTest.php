<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_dapat_login_dan_masuk_ke_dashboard_admin(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@pmb.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@pmb.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function calon_mahasiswa_dapat_login_dan_masuk_ke_dashboard_mahasiswa(): void
    {
        $mahasiswa = User::factory()->create([
            'email' => 'mahasiswa@pmb.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $response = $this->post('/login', [
            'email' => 'mahasiswa@pmb.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticatedAs($mahasiswa);
    }

    /** @test */
    public function user_yang_belum_login_tidak_dapat_membuka_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function calon_mahasiswa_tidak_dapat_membuka_halaman_admin(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    /** @test */
    public function login_gagal_jika_format_email_salah(): void
    {
        $response = $this->post('/login', [
            'email' => 'bukan-email',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
