<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RegionSeeder::class,
            StateSeeder::class,
            CountySeeder::class,

            OccupationSeeder::class,
            MenusSeeder::class,
            PronounSeeder::class,
            ProfileSeeder::class,
            GovernmentAgencySeeder::class,
            UserSeeder::class,
            GenreSeeder::class,
            BreedSeeder::class,
            TargetPublicSeeder::class,
            TypeReasonDelayVaccineSeeder::class,
            GovernmentOfficeSeeder::class,
            // GovernmentOfficeUserSeeder::class,
            ReasonDelayVaccineSeeder::class,
            ReasonDelayVaccineGovernmentOfficeSeeder::class,
            ReasonDelayVaccineTargetPublicSeeder::class,
            TypeStatusVaccinationSeeder::class,
            VaccineSeeder::class,
            // VaccineRoomSeeder::class,
            DeadlineSeeder::class,
            ForwardingSeeder::class,

            CnesEstabelecimentoSeeder::class,
            GroupSeeder::class,
            SubGroupSeeder::class,
            UnderSubGroupSeeder::class,

            GroupUserSeeder::class,
            SubGroupUserSeeder::class,
            UnderSubGroupUserSeeder::class,

            // AlertSeeder::class,
            AccessionSeeder::class,

            CandidatoSeeder::class,
            AlertJourneySeeder::class,

            CommentRecordSeeder::class,

            ReasonCloseAlertSeeder::class,
            ReasonNotAppliedVaccineSeeder::class,
            MenusOccupationSeeder::class
        ]);
    }
}
