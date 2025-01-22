<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Faculty;
use App\Models\University;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            'Univerzita sv. Cyrila a Metoda v Trnave' => [
                'Filozofická fakulta' => [
                    'Katedra psychológie',
                    'Katedra etnológie a mimoeurópskych štúdií',
                    'Katedra filozofie a aplikovanej filozofie',
                    'Katedra historických vied a stredoeurópskych štúdií',
                    'Katedra rusistiky',
                    'Katedra germanistiky',
                    'Katedra anglistiky a amerikanistiky',
                    'Katedra slovenského jazyka a literatúry',
                    'Katedra pedagogiky',
                ],
                'Fakulta masmediálnej komunikácie' => [
                    'Katedra masmediálnej komunikácie',
                    'Katedra marketingovej komunikácie',
                    'Katedra umeleckej komunikácie',
                    'Katedra mediálnej výchovy',
                    'Katedra jazykovej komunikácie',
                    'Katedra digitálnych hier',
                ],
                'Fakulta prírodných vied' => [
                    'Katedra aplikovanej informatiky a matematiky',
                    'Katedra chémie',
                    'Katedra biotechnológií',
                    'Katedra biológie',
                    'Katedra ekochémie a rádioekológie',
                    'Katedra biofyziky',
                    'Katedra odbornej jazykovej prípravy',
                ],
                'Fakulta sociálnych vied' => [
                    'Katedra politických vied',
                    'Katedra sociálnej práce',
                ],
                'Fakulta zdravotníckych vied' => [
                    'Katedra fyzioterapie',
                    'Katedra rádiológie',
                    'Katedra odbornej prípravy',
                    'Katedra klinických disciplín',
                ],
            ],
            'Univerzita Konštantína Filozofa v Nitre' => [
                'Fakulta prírodných vied a informatiky' => [
                    'Katedra botaniky a genetiky',
                    'Katedra ekológie a environmentalistiky',
                    'Katedra fyziky',
                    'Katedra geografie, geoinformatiky a regionálneho rozvoja',
                    'Katedra chémie',
                    'Katedra informatiky',
                    'Katedra matematiky',
                    'Katedra zoológie a antropológie',
                    'Ústav ekonomiky a manažmentu',
                ],
                'Fakulta sociálnych vied a zdravotníctva' => [
                    'Katedra klinických disciplín a urgentnej medicíny',
                    'Katedra ošetrovateľstva',
                    'Katedra psychologických vied',
                    'Katedra sociálnej práce a sociálnych vied',
                    'Ústav aplikovanej psychológie',
                    'Ústav romologických štúdií',
                ],
                'Fakulta stredoeurópskych štúdií' => [
                    'Katedra cestovného ruchu',
                    'Ústav maďarskej jazykovedy a literárnej vedy',
                    'Ústav pre vzdelávanie pedagógov',
                    'Ústav stredoeurópskych jazykov a kultúr',
                ],
                'Filozofická fakulta' => [
                    'Katedra anglistiky a amerikanistiky',
                    'Katedra archeológie',
                    'Katedra etiky a estetiky',
                    'Katedra filozofie a politológie',
                    'Katedra histórie',
                    'Katedra masmediálnej komunikácie a reklamy',
                    'Katedra romanistiky a germanistiky',
                    'Katedra slovanských filológií',
                    'Katedra translatológie',
                    'Katedra žurnalistiky a nových médií',
                    'Ústav literárnej a umeleckej komunikácie',
                    'Ústav manažmentu kultúry a turizmu, kulturológie a etnológie',
                    'Ústav pre výskum kultúrneho dedičstva Konštantína a Metoda',
                ],
                'Pedagogická fakulta' => [
                    'Katedra anglického jazyka a kultúry',
                    'Katedra hudby',
                    'Katedra pedagogiky',
                    'Katedra pedagogickej a školskej psychológie',
                    'Katedra techniky a informačných technológii',
                    'Katedra telesnej výchovy a športu',
                    'Katedra výtvarnej tvorby a výchovy',
                ],
            ],
            'Univerzita Mateja Bela v Banskej Bystrici' => [
                'Ekonomická fakulta' => [
                    'Katedra cestovného ruchu',
                    'Katedra ekonómie',
                    'Katedra ekonomiky a manažmentu podniku',
                    'Katedra financií a účtovníctva',
                    'Katedra kvantitatívnych metód a informačných systémov',
                    'Katedra odbornej jazykovej komunikácie',
                    'Katedra verejnej ekonomiky a regionálneho rozvoja',
                    'Inštitút manažérskych systémov, Poprad',
                    'Výskumné a inovačné centrum',
                ],
                'Fakulta politických vied a medzinárodných vzťahov' => [
                    'Katedra politológie',
                    'Katedra medzinárodných vzťahov a diplomacie',
                    'Katedra bezpečnostných štúdií',
                ],
                'Fakulta prírodných vied' => [
                    'Katedra biológie a environmentálnych štúdií',
                    'Katedra fyziky',
                    'Katedra geografie a geológie',
                    'Katedra chémie',
                    'Katedra informatiky',
                    'Katedra matematiky',
                    'Katedra techniky a technológií',
                    'Centrum geoinformatiky a digitálnych technológií',
                ],
                'Fakulta telesnej výchovy, športu a zdravia' => [
                    'Katedra telesnej výchovy a športu',
                ],
                'Filozofická fakulta' => [
                    'Katedra anglistiky a amerikanistiky',
                    'Katedra filozofie',
                    'Katedra germanistiky',
                    'Katedra histórie',
                    'Katedra romanistiky',
                    'Katedra slovanských jazykov',
                    'Katedra slovenského jazyka a komunikácie',
                    'Katedra slovenskej literatúry a literárnej vedy',
                    'Katedra sociológie a sociálnej antropológie',
                ],
                'Pedagogická fakulta' => [
                    'Centrum umenia a kultúry',
                    'Katedra elementárnej a predškolskej pedagogiky',
                    'Katedra pedagogiky a andragogiky',
                    'Katedra psychológie',
                    'Katedra sociálnej práce',
                    'Katedra teológie a katechetiky',
                ],
                'Právnická fakulta' => [
                    'Katedra občianskeho práva a pracovného práva',
                    'Katedra obchodného práva a hospodárskeho práva',
                    'Katedra dejín štátu a práva',
                    'Katedra trestného práva, kriminológie, kriminalistiky a forenzných disciplín',
                    'Katedra finančného práva a správneho práva',
                    'Katedra medzinárodného práva, európskeho práva a právnej komunikácie',
                    'Katedra ústavného práva a teórie práva',
                    'Ústav klinického vzdelávania pri PrF UMB',
                    'Ústav kriminologického a kriminalistického výskumného centra pri PrF UMB',
                ],
            ],
        ];

        foreach ($universities as $universityName => $faculties) {
            $university = University::where('name', $universityName)->first();

            if ($university) {
                foreach ($faculties as $facultyName => $departmentList) {
                    $faculty = Faculty::where('name', $facultyName)
                        ->where('uni_id', $university->id)
                        ->first();

                    if ($faculty) {
                        foreach ($departmentList as $departmentName) {
                            // Skontrolujeme, či katedra už existuje
                            if (!DB::table('departments')
                                ->where('name', $departmentName)
                                ->where('faculty_id', $faculty->id)
                                ->exists()) {
                                DB::table('departments')->insert([
                                    'uni_id' => $university->id,
                                    'faculty_id' => $faculty->id,
                                    'name' => $departmentName,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}