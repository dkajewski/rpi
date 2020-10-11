<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertScreenBrightnessConfigurationHours();
    }

    /**
     * Insert initial screen brightness values into db
     */
    private function insertScreenBrightnessConfigurationHours()
    {
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[] = [
                'configuration_group' => 'screen_brightness',
                'configuration_key' => "SB_HOUR_$i",
                'configuration_title' => "Screen brightness hour: $i",
                'configuration_comment' => '',
            ];

            if ($i >= 21 || $i <= 6) {
                $data[$i]['configuration_value'] = 0;
            } else {
                $data[$i]['configuration_value'] = 50;
            }
        }

        \App\Configuration::insert($data);
    }
}
