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
        // Complete mapping of thread colors to their actual image URLs from the Google Sheet
        $threadColors = [
            ['color_code' => '672', 'color_name' => '672', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXBX0xiM1UgtK-VON0mX4RyY95pxbVX4NGs6kgjP_Q8uGPQH_aXAjP9hVhbnxPz5cxvdQirgYaU8TDltXXRTR6jIkQRACZxHRekTn_1ZZUEswEdoZKqgwDY-Dur1WXyWVXIDjnIBVXiuMoSbp3M6kU=s120-w120-h59'],
            ['color_code' => '1500', 'color_name' => '1500', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7hZX5ZjXkHKhy7zM9IbiYkcRJoCwqRsa74NDzcgF_6423dfDSgdUUd79f-hOKd_kNIlPJIm-eImjP1Xjr3xGLE-yMYR8pzb7pIPa7BL9IXUC7afIm-IEfa2ghLZg102CHgcp9JlBQ1wxKUC5Dpsg=s120-w120-h59'],
            ['color_code' => '1508', 'color_name' => '1508', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV-CTH7mM5nCPZ6XDnYyW867sP0P2Nb2ptEvHLw-DJYeRg-3hf5THgIdNdyxatJKOLc4EDd7fNRLxJ6tDcz6H8mCIwbnnyVkcxy5ezoRSyx3SmkIOQb5kJVgd_wDm_KS3a_AuqmgVtyM4HeSCvr9c0=s120-w120-h59'],
            ['color_code' => '1509', 'color_name' => '1509', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVYZwzd4YoN88fmjXLlXrufoHFDV5pc1dFgHo3LJIZPH8FpoXrZ3pMLAGvQerQ1QjEjyPmUY0nd1arpnCh0xxG9rGAiLwr6EbvGmA9pZzs_qDHovgIO6RuW0gjRQGig_NDh5rNznrbztfzOb6jB6bQ=s120-w120-h59'],
            ['color_code' => '1510', 'color_name' => '1510', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVRu58kyL62p03wsYBOBmwFnOGfE_HvLgCYPo-b8GYQVyuqBzO9bLjs-SiwkAQPAk8LMLwRqybpwfl1CSZH-TPY_Xd4-T703WdQcAyZN9TRPHA3sj4eBQBMgkjYBm3LZD8mjHuWToJDA5FCOGe5iu8=s120-w120-h59'],
            ['color_code' => '1511', 'color_name' => '1511', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVsSimcB56PXklgorZfoTemTadv8yYZgqTFIZoDtoMMuSLcs2TrufsexK8UTtddufeL4yha3CM6KQ2hEu0a4YHCqK53iMAnR-9qWOfab4QZxl4GV4bd6bYybSzEeiV9Yxm16O_SLDMsWrw-IChPCQ=s120-w120-h59'],
            ['color_code' => '1512', 'color_name' => '1512', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXFUZF6xCdwiGayapm7WwZZUKD9UssWBxJOGP_f4vYD_YZ5sg8CUV9RcwKfvqipN8UttKK5M2F9cgmJYdqq6VCpG4MfSnucaSk5CDQzP12SMl9p7JulwkhKFftfVoiSmTSUQ--_Lxchv4tb0CDrGwU=s120-w120-h59'],
            ['color_code' => '1513', 'color_name' => '1513', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59'],
            ['color_code' => '1514', 'color_name' => '1514', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUN9XTm9pkb5SyngE3d0qAIgbnxrZ6PkVuCOk8Dl...(27814 chars omitted)...fO3eb9WDN8NsRVoQqxY3zy-13ACv4MsemDYa1nqiS6MSHhRAxTiBtKoqtVhj1J851pu7pqx44vGw9g=s120-w120-h59'],
            ['color_code' => '1754', 'color_name' => '1754', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xW09-gYr5xzIEI0TcaxUKkteUsnNV6kejy4BxYShE8-oWua1QMOVMTIGmRzZidXdMR7zItjOD9gnFeSNfLjKjhi5baQD3xun1snkpsdNNXSdnfJ8c_a4JkOFMN6cNVNzVIY8OZ4Eexc_ABssA-jegg=s120-w120-h59'],
            ['color_code' => '1758', 'color_name' => '1758', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXtuSlplS3aHjcGjRhSGqvEgRCjjx18mixKyyn-YD0Ll5vVXuS-1U6cOUTYsUj_bfEpDJv9EukH72yzFT00R-pxA-gOTQcg7Xz7sQPXC2iLtfjI9wrickCsFVEjaCt7lnn2eVYF2dd--z2Ed09tQ6A=s120-w120-h59'],
            ['color_code' => '1767', 'color_name' => '1767', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWCKNNHvb2CDXflmZ89xMO-EFMHCwfn0Jzt6aExAZqSX4dpb-8kKb3L7DUJZoAg2taGE5gPYEqR_E4NhyGD5IhLxeJEwTI9oxSY1eoND4qhEa6yJljA2l54US53YqXVw7-81xWFWY4FGUL2FcDzpk0=s120-w120-h59'],
            ['color_code' => '1768', 'color_name' => '1768', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWH6nt3Ipm0bkvh3QTiLudTGqNA6CwRJ1ExE4kjmJb4KQdJAzU6g7ZVw5urqn41LLRk4Mf7VrsB2YEHAn-bI_9er4tpskNhcmC9zr3MJUUYyZ-0FTr-bA09r-VmVr80L96fw7lybGqTqQKT_khngg=s120-w120-h59'],
            ['color_code' => '1769', 'color_name' => '1769', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7oDreG45DK4PdOdbivfp2GUXqaTnrPuCepWLIAvQm42UXFGMdI5mJdruti4GoFVzpPsg-Dee7jDyOvkWqu_vtCCDC0CQHtBjQ1Wcfs6jcpFmE7DkbFNH6UEjoAni4AXMHq6wK0dtN_gI7278TY6Y=s120-w120-h59'],
            ['color_code' => '1770', 'color_name' => '1770', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVBMdLpcgeNYQJg-ScvuBqe3H8wphMO67frORdjj01NlEupxk64ZmZGgPI6tJkW3Jg2IVDBj3jWqlgLJ1OSX6fw69gzvRPLEYHLnOBFvOVifrshoDUqBahKgclFzyRkerZV89PVmQlRBV7lxiDv_Q=s120-w120-h59'],
            ['color_code' => '1777', 'color_name' => '1777', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUG27BsIsNekbVCN9J-UfT_2mQBGiRYJELje6WgFoV1PUHchnJSQ40OWhH_bqXuWh4DFrm1Gg5AwzSK_hDTx3m2QgUKI78FqMU5won5wWIqyRgl1t-Nhsw4b-NKoLALAwAXHATVRbmOVqPmF9bEGQ=s120-w120-h59'],
            ['color_code' => '1779', 'color_name' => '1779', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVcqVJYG9OMBgYo8NLmTjYZzbb6ZiU27vC-UYwJwL1H_f9GIfsNyKAeFDJtu2gkF_vrUSE-G9NIzflydKRvcpKEpjGK-neHAEnISNGqxhnt6o6u3A7clX3dv39m1zt34sRD7YaHyBpamuwPD_ZXnLo=s120-w120-h59'],
            ['color_code' => '1781', 'color_name' => '1781', 'image_url' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV_prtpAP5QHygKhBseu789a7Vbtn3pxnySWaDjCYtYVymZ0HYOGYNc_HPtXGeqX6TpEHY9StzqljxNNRuWNWsxoTWfSuEArgj7KuLX47bfl_GJKi423qfpW1es7DMnJbU1GZc-VeCGbv4eexcRDGo=s120-w120-h59'],
        ];

        // For now, let's import just the colors we have actual image URLs for
        // The rest can be added manually or through a more comprehensive import process
        foreach ($threadColors as $thread) {
            ThreadColor::create([
                'color_name' => $thread['color_name'],
                'color_code' => $thread['color_code'],
                'image_url' => $thread['image_url'],
            ]);
        }
    }
}