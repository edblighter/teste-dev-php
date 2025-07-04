<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $countries = json_decode(file_get_contents(database_path('data/countries.json')), true);

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'code'            => $country['cca3'] ?? null,
                'name'            => $country['name']['common'] ?? null,
                'iso_3166_2'      => $country['cca2'] ?? null,
                'iso_3166_3'      => $country['cca3'] ?? null,
                'numeric_code'    => $country['ccn3'] ?? null,
                'tld'             => $country['tld'][0] ?? null,
                'phonecode'       => isset($country['idd']['root'], $country['idd']['suffixes'][0])
                    ? $country['idd']['root'] . $country['idd']['suffixes'][0]
                    : null,
                'capital'         => $country['capital'][0] ?? null,
                'currency'        => array_keys($country['currencies'] ?? [])[0] ?? null,
                'currency_name'   => isset($country['currencies']) ? collect($country['currencies'])->first()['name'] ?? null : null,
                'currency_symbol' => isset($country['currencies']) ? collect($country['currencies'])->first()['symbol'] ?? null : null,
                'region'          => $country['region'] ?? null,
                'subregion'       => $country['subregion'] ?? null,
                'emoji'           => $country['flag'] ?? null,
                'active'          => true,
            ]);
        }
    }
}
