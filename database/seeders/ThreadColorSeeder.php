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
        // Generate ALL thread colors from the Google Sheet range (672 to 1781)
        // This creates a comprehensive list of all possible thread colors
        $threadColors = [];
        
        // Create all thread colors in the range with systematic image URL generation
        // Based on the pattern from the Google Sheet, we'll create all colors
        $allColors = [];
        
        // Generate all colors from 672 to 1781
        for ($i = 672; $i <= 1781; $i++) {
            $allColors[] = (string)$i;
        }
        
        // Add some specific colors that we know exist
        $specificColors = ['672', '1500', '1508', '1509', '1510', '1511', '1512', '1513', '1514', '1515', '1516', '1517', '1518', '1519', '1520', '1521', '1522', '1523', '1524', '1525', '1526', '1527', '1528', '1529', '1530', '1754', '1758', '1767', '1768', '1769', '1770', '1777', '1779', '1781'];
        
        // Create thread color entries for all colors
        foreach ($allColors as $colorCode) {
            // Use a systematic approach to generate image URLs
            // For now, we'll use a placeholder that can be updated later
            $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59';
            
            // Use specific URLs for colors we know exist
            if (in_array($colorCode, $specificColors)) {
                switch ($colorCode) {
                    case '672':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXBX0xiM1UgtK-VON0mX4RyY95pxbVX4NGs6kgjP_Q8uGPQH_aXAjP9hVhbnxPz5cxvdQirgYaU8TDltXXRTR6jIkQRACZxHRekTn_1ZZUEswEdoZKqgwDY-Dur1WXyWVXIDjnIBVXiuMoSbp3M6kU=s120-w120-h59';
                        break;
                    case '1500':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7hZX5ZjXkHKhy7zM9IbiYkcRJoCwqRsa74NDzcgF_6423dfDSgdUUd79f-hOKd_kNIlPJIm-eImjP1Xjr3xGLE-yMYR8pzb7pIPa7BL9IXUC7afIm-IEfa2ghLZg102CHgcp9JlBQ1wxKUC5Dpsg=s120-w120-h59';
                        break;
                    case '1508':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV-CTH7mM5nCPZ6XDnYyW867sP0P2Nb2ptEvHLw-DJYeRg-3hf5THgIdNdyxatJKOLc4EDd7fNRLxJ6tDcz6H8mCIwbnnyVkcxy5ezoRSyx3SmkIOQb5kJVgd_wDm_KS3a_AuqmgVtyM4HeSCvr9c0=s120-w120-h59';
                        break;
                    case '1509':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVYZwzd4YoN88fmjXLlXrufoHFDV5pc1dFgHo3LJIZPH8FpoXrZ3pMLAGvQerQ1QjEjyPmUY0nd1arpnCh0xxG9rGAiLwr6EbvGmA9pZzs_qDHovgIO6RuW0gjRQGig_NDh5rNznrbztfzOb6jB6bQ=s120-w120-h59';
                        break;
                    case '1510':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVRu58kyL62p03wsYBOBmwFnOGfE_HvLgCYPo-b8GYQVyuqBzO9bLjs-SiwkAQPAk8LMLwRqybpwfl1CSZH-TPY_Xd4-T703WdQcAyZN9TRPHA3sj4eBQBMgkjYBm3LZD8mjHuWToJDA5FCOGe5iu8=s120-w120-h59';
                        break;
                    case '1511':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVsSimcB56PXklgorZfoTemTadv8yYZgqTFIZoDtoMMuSLcs2TrufsexK8UTtddufeL4yha3CM6KQ2hEu0a4YHCqK53iMAnR-9qWOfab4QZxl4GV4bd6bYybSzEeiV9Yxm16O_SLDMsWrw-IChPCQ=s120-w120-h59';
                        break;
                    case '1512':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXFUZF6xCdwiGayapm7WwZZUKD9UssWBxJOGP_f4vYD_YZ5sg8CUV9RcwKfvqipN8UttKK5M2F9cgmJYdqq6VCpG4MfSnucaSk5CDQzP12SMl9p7JulwkhKFftfVoiSmTSUQ--_Lxchv4tb0CDrGwU=s120-w120-h59';
                        break;
                    case '1513':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59';
                        break;
                    case '1514':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUN9XTm9pkb5SyngE3d0qAIgbnxrZ6PkVuCOk8Dl...(27814 chars omitted)...fO3eb9WDN8NsRVoQqxY3zy-13ACv4MsemDYa1nqiS6MSHhRAxTiBtKoqtVhj1J851pu7pqx44vGw9g=s120-w120-h59';
                        break;
                    case '1754':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xW09-gYr5xzIEI0TcaxUKkteUsnNV6kejy4BxYShE8-oWua1QMOVMTIGmRzZidXdMR7zItjOD9gnFeSNfLjKjhi5baQD3xun1snkpsdNNXSdnfJ8c_a4JkOFMN6cNVNzVIY8OZ4Eexc_ABssA-jegg=s120-w120-h59';
                        break;
                    case '1758':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXtuSlplS3aHjcGjRhSGqvEgRCjjx18mixKyyn-YD0Ll5vVXuS-1U6cOUTYsUj_bfEpDJv9EukH72yzFT00R-pxA-gOTQcg7Xz7sQPXC2iLtfjI9wrickCsFVEjaCt7lnn2eVYF2dd--z2Ed09tQ6A=s120-w120-h59';
                        break;
                    case '1767':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWCKNNHvb2CDXflmZ89xMO-EFMHCwfn0Jzt6aExAZqSX4dpb-8kKb3L7DUJZoAg2taGE5gPYEqR_E4NhyGD5IhLxeJEwTI9oxSY1eoND4qhEa6yJljA2l54US53YqXVw7-81xWFWY4FGUL2FcDzpk0=s120-w120-h59';
                        break;
                    case '1768':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWH6nt3Ipm0bkvh3QTiLudTGqNA6CwRJ1ExE4kjmJb4KQdJAzU6g7ZVw5urqn41LLRk4Mf7VrsB2YEHAn-bI_9er4tpskNhcmC9zr3MJUUYyZ-0FTr-bA09r-VmVr80L96fw7lybGqTqQKT_khngg=s120-w120-h59';
                        break;
                    case '1769':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7oDreG45DK4PdOdbivfp2GUXqaTnrPuCepWLIAvQm42UXFGMdI5mJdruti4GoFVzpPsg-Dee7jDyOvkWqu_vtCCDC0CQHtBjQ1Wcfs6jcpFmE7DkbFNH6UEjoAni4AXMHq6wK0dtN_gI7278TY6Y=s120-w120-h59';
                        break;
                    case '1770':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVBMdLpcgeNYQJg-ScvuBqe3H8wphMO67frORdjj01NlEupxk64ZmZGgPI6tJkW3Jg2IVDBj3jWqlgLJ1OSX6fw69gzvRPLEYHLnOBFvOVifrshoDUqBahKgclFzyRkerZV89PVmQlRBV7lxiDv_Q=s120-w120-h59';
                        break;
                    case '1777':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUG27BsIsNekbVCN9J-UfT_2mQBGiRYJELje6WgFoV1PUHchnJSQ40OWhH_bqXuWh4DFrm1Gg5AwzSK_hDTx3m2QgUKI78FqMU5won5wWIqyRgl1t-Nhsw4b-NKoLALAwAXHATVRbmOVqPmF9bEGQ=s120-w120-h59';
                        break;
                    case '1779':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVcqVJYG9OMBgYo8NLmTjYZzbb6ZiU27vC-UYwJwL1H_f9GIfsNyKAeFDJtu2gkF_vrUSE-G9NIzflydKRvcpKEpjGK-neHAEnISNGqxhnt6o6u3A7clX3dv39m1zt34sRD7YaHyBpamuwPD_ZXnLo=s120-w120-h59';
                        break;
                    case '1781':
                        $imageUrl = 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV_prtpAP5QHygKhBseu789a7Vbtn3pxnySWaDjCYtYVymZ0HYOGYNc_HPtXGeqX6TpEHY9StzqljxNNRuWNWsxoTWfSuEArgj7KuLX47bfl_GJKi423qfpW1es7DMnJbU1GZc-VeCGbv4eexcRDGo=s120-w120-h59';
                        break;
                }
            }
            
            $threadColors[] = [
                'color_code' => $colorCode,
                'color_name' => $colorCode,
                'image_url' => $imageUrl,
            ];
        }

        // Import ALL thread colors
        foreach ($threadColors as $thread) {
            ThreadColor::create([
                'color_name' => $thread['color_name'],
                'color_code' => $thread['color_code'],
                'image_url' => $thread['image_url'],
            ]);
        }
    }
}