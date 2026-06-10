<?php

namespace Tests\Feature\Marketplace;

use App\Models\ArsitekProfile;
use App\Models\ClientProfile;
use App\Models\Proposal;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenBiddingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_active_project(): void
    {
        $client = User::factory()->client()->create();
        ClientProfile::create([
            'user_id' => $client->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($client)->post(route('client.proyek.store'), [
            'title' => 'Desain Rumah Tumbuh',
            'description' => 'Butuh desain rumah tumbuh dua lantai dengan konsep tropis.',
            'budget' => 35000000,
            'category' => 'Residensial (Rumah, Villa, Apartemen)',
            'location' => 'Malang',
        ]);

        $response->assertRedirect(route('client.proyek.index'));

        $this->assertDatabaseHas('proyek', [
            'user_id' => $client->id,
            'title' => 'Desain Rumah Tumbuh',
            'status' => 'aktif',
        ]);
    }

    public function test_guest_can_view_active_project_marketplace(): void
    {
        Proyek::factory()->create([
            'title' => 'Desain Vila Terbuka',
            'status' => 'aktif',
        ]);

        $this->get(route('proyek.index'))
            ->assertOk()
            ->assertSee('Eksplorasi Proyek Arsitektur');
    }

    public function test_architect_can_submit_proposal_to_active_project(): void
    {
        $arsitek = User::factory()->arsitek()->create();
        ArsitekProfile::factory()->create([
            'user_id' => $arsitek->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        $project = Proyek::factory()->create();

        $response = $this->actingAs($arsitek)->post(route('arsitek.proposal.store', $project->id), [
            'bid_amount' => 25000000,
            'estimated_time' => 21,
            'description' => 'Saya menawarkan konsep desain tropis yang efisien dan mudah dibangun.',
        ]);

        $response->assertRedirect(route('arsitek.proposal.index'));

        $this->assertDatabaseHas('proposal', [
            'user_id' => $arsitek->id,
            'proyek_id' => $project->id,
            'status' => 'pending',
        ]);
    }

    public function test_architect_cannot_submit_duplicate_proposal(): void
    {
        $arsitek = User::factory()->arsitek()->create();
        ArsitekProfile::factory()->create([
            'user_id' => $arsitek->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        $project = Proyek::factory()->create();

        Proposal::factory()->create([
            'user_id' => $arsitek->id,
            'proyek_id' => $project->id,
        ]);

        $response = $this->actingAs($arsitek)->from(route('proyek.show', $project->id))
            ->post(route('arsitek.proposal.store', $project->id), [
                'bid_amount' => 26000000,
                'estimated_time' => 24,
                'description' => 'Proposal kedua yang seharusnya ditolak.',
            ]);

        $response->assertRedirect(route('proyek.show', $project->id));
        $response->assertSessionHas('error', 'Anda sudah mengirimkan proposal untuk proyek ini.');

        $this->assertSame(1, Proposal::where('user_id', $arsitek->id)->where('proyek_id', $project->id)->count());
    }

    public function test_client_accepts_one_proposal_and_other_proposals_are_rejected(): void
    {
        $client = User::factory()->client()->create();
        ClientProfile::create([
            'user_id' => $client->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        $project = Proyek::factory()->for($client, 'user')->create();
        $acceptedProposal = Proposal::factory()->for($project, 'proyek')->create();
        $otherProposal = Proposal::factory()->for($project, 'proyek')->create();

        $response = $this->actingAs($client)->post(route('client.proposal.terima', $acceptedProposal->id));

        $response->assertRedirect(route('client.proyek.show', $project->id));

        $this->assertSame('diterima', $acceptedProposal->refresh()->status);
        $this->assertSame('ditolak', $otherProposal->refresh()->status);
        $this->assertSame('ditutup', $project->refresh()->status);
    }

    public function test_architect_cannot_update_accepted_or_rejected_proposal(): void
    {
        $arsitek = User::factory()->arsitek()->create();
        ArsitekProfile::factory()->create([
            'user_id' => $arsitek->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        $acceptedProposal = Proposal::factory()->accepted()->create([
            'user_id' => $arsitek->id,
        ]);

        $this->actingAs($arsitek)->put(route('arsitek.proposal.update', $acceptedProposal->id), [
            'bid_amount' => 30000000,
            'estimated_time' => 30,
            'description' => 'Update yang tidak boleh diterima.',
        ])->assertNotFound();
    }
}
