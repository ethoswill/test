<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThreadColor;

class ThreadColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of thread color data from the Google Sheet
        $threadColors = [
            ['color_code' => '672', 'color_name' => '672', 'color_number' => 672, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWM6Fle35zGrYE5hD2Kcltq7qH6s_L9oZfYwhUbOKuKX-gRki3bLYFv_Ki9SCQydqTPGklcrELcfKUAr4ZtnTwyYaZle9xdILOoQvlYNtP-9XA1z_akTWkDt46XJPVWvLmcmN7wuKUokUdrEjdrFZQ'],
            ['color_code' => '1500', 'color_name' => '1500', 'color_number' => 1500, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUZlILtGzAJGWHyY6c8p04xBWdbGqgv_xNGnf6Agr06P-6eT17tWK3Jr-JKbZtV2SjZULGIFJ_Cmk3HmnXaHyDnoHlUBtSwNA0IS8NKsTJEZ-hs9B2pEEZYoDNDEU5uf6nbtsEnTrZ6HEWUdEBFUmQ'],
            ['color_code' => '1508', 'color_name' => '1508', 'color_number' => 1508, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xU5cy-flUvIch63U-X7jWW2unEu0a_ULpINkGRiFwpAksQD7lR6_buSNTD9UGLhTJbi-Y1xoF02Bn_HOICoykSoz3aklGxz6cD9e0Zg5h1VDsWXjDL8ngpUA_KaiQqdJ2cUa8MDlmGl9QX1_xnXCoc'],
            ['color_code' => '1509', 'color_name' => '1509', 'color_number' => 1509, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xU5BY12KffBf0BaadOIueAXUNsIlM1-TXmBbfghuH2FXSwVzPGtYPP6pfFo6UwwI5o-CmF1ZLbArqxEQ5UV-18Fl7iA1KZW6U9BTkUZaXeKo7N3isPNRJ1Bo8F0f_x0b3IJ71uYUOqyDZM2c71m0zk'],
            ['color_code' => '1510', 'color_name' => '1510', 'color_number' => 1510, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXMesJ3Z8y5_SlqD0hwRdaGPEVclsyuFT-6iT9Cekj6pwGqJQ4zRN3A5PQ5NSvMIdhLHrycuKhxODN0FaYBOM1T8QQjDSwmbpAn02mjH01VLSDjYxDmBqpVd7sCKWoXZkXB53IBO11InQR--hk1u1U'],
            ['color_code' => '1511', 'color_name' => '1511', 'color_number' => 1511, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXApjz9eUCJz63pXPZhNCrdL562_xn4XZmWOEpr6BdMSMXzJz4uDb7IxqB_-XVIMfvkj8RVazRK_9Hw8xu0qafxP1_C1BeIaHQMHON_5oSmjmpfEKTq7aspa8wfAYsJ-bmNiEYw7dFVb0f_DcyHCw'],
            ['color_code' => '1512', 'color_name' => '1512', 'color_number' => 1512, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUst9Z9VqIIu6wMC1n2RKkfhgWpIuzh0uhocuzjczyrR2vgbdFvSRZMGPIFOo5yAoR_jsu5qqxY0zj6EXPk1sq8R4AIQSfIG60aJsD6w3D-1mnCv3SIAVeQ46oQY6eK6NNNU-eIgmZMe9UuiTkFq2E'],
            ['color_code' => '1513', 'color_name' => '1513', 'color_number' => 1513, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUaSq6KTuacAKIYigGzXJOHEjQAofVJP4WQto3wOPj09LTGvrG3IYLcbIPME3G7PCYTnUNN9EFH7DggpybSPXmxy2FxlvlGloCukmrldYiKgPyG5OBg4Ex-SvJISP4UI3QdFg8kBhfd9CIVh_IfVg'],
            ['color_code' => '1514', 'color_name' => '1514', 'color_number' => 1514, 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXI2HmENY3CzFikbiz8_yo9nTH92lh3jJa1b0-91N3y_7MJeC0uCDxds16Bw6-lU96vDaDrbR9T9D8VnYO9jGb-dh_6Qn_3YMukVKI1cH_5o03xrIX8KFuFWmwRLa1z1vWmPyqnAYeO9_DT-4Ylo'],
            // Continue with more entries from the sheet...
        ];

        foreach ($threadColors as $thread) {
            ThreadColor::create([
                'color_name' => $thread['color_name'],
                'color_code' => $thread['color_code'],
                'image_url' => $thread['image_url'],
            ]);
        }
    }
}

