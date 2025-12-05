<?php

namespace Database\Factories;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LeaveApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(1, 60));
        $endDate = $startDate->copy()->addDays($this->faker->numberBetween(1, 5));
        
        // Calculate total working days (excluding weekends)
        $totalDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $totalDays++;
            }
            $currentDate->addDay();
        }

        return [
            'user_id' => User::factory(), // Akan diganti di seeder jika perlu
            'type' => $this->faker->randomElement(['annual', 'sick']),
            'application_date' => Carbon::now()->subDays($this->faker->numberBetween(1, 30)),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $this->faker->sentence(10),
            'doctor_note' => $this->faker->boolean(20) ? 'notes/fake_doctor_note.pdf' : null, // 20% kemungkinan ada catatan dokter
            'address_during_leave' => $this->faker->address(),
            'emergency_contact' => $this->faker->phoneNumber(),
            'status' => 'pending', // Status default
            'leader_id' => null,
            'hrd_id' => null,
        ];
    }

    /**
     * Indicate that the application is approved by the leader and is waiting for HRD.
     */
    public function approvedByLeader(User $leader)
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved_by_leader',
            'leader_id' => $leader->id,
            'leader_note' => $this->faker->sentence(5),
            'leader_action_at' => Carbon::now(),
        ]);
    }
    
    /**
     * Indicate that the application is fully approved.
     */
    public function approved(User $leader, User $hrd)
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'leader_id' => $leader->id,
            'leader_note' => $this->faker->sentence(5),
            'leader_action_at' => Carbon::now()->subHours(5),
            'hrd_id' => $hrd->id,
            'hrd_note' => $this->faker->sentence(5),
            'hrd_action_at' => Carbon::now(),
        ]);
    }

    /**
     * Indicate that the application is rejected.
     */
    public function rejected(User $hrd)
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'hrd_id' => $hrd->id,
            'hrd_note' => 'Rejected due to insufficient quota or manpower needs.',
            'hrd_action_at' => Carbon::now(),
        ]);
    }
}