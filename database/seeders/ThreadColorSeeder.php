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
        // All thread colors from the CSV file
        $threadColors = [
            '672', '1500', '1508', '1509', '1510', '1511', '1512', '1513', '1514', '1515', '1516', '1517', '1518', '1519', '1527', '1529', '1541', '1545', '1548', '1549', '1552', '1553', '1554', '1556', '1557', '1560', '1564', '1567', '1568', '1573', '1578', '1584', '1588', '1589', '1595', '1596', '1597', '1598', '1599', '1600', '1601', '1602', '1603', '1604', '1605', '1606', '1607', '1608', '1609', '1611', '1616', '1620', '1622', '1624', '1629', '1630', '1631', '1633', '1635', '1637', '1638', '1639', '1648', '1649', '1650', '1651', '1656', '1658', '1660', '1661', '1668', '1681', '1686', '1687', '1701', '1702', '1707', '1709', '1712', '1713', '1721', '1728', '1731', '1734', '1736', '1744', '1745', '1748', '1749', '1752', '1754', '1758', '1767', '1768', '1769', '1770', '1777', '1779', '1781', '1782', '1783', '1784', '1785', '1786', '1787', '1801', '1809', '1815', '1816', '1818', '1819', '1821', '1823', '1824', '1833', '1834', '1835', '1837', '1848', '1850', '1851', '1855', '1867', '1870', '1872', '1882', '1883', '1885', '1886', '1887', '1900', '1901', '1902', '1903', '1904', '1905', '1906', '1907', '1909', '1910', '1915', '1917', '1919', '1920', '1921', '1925', '1927', '1928', '1929', '1933', '1935', '1936', '1937', '1938', '1940', '1941', '1944', '1946', '1947', '1948', '1950', '1954', '1955', '1956', '1966', '1968', '1970', '1972', '1973', '1974', '1978', '1981', '1982', '1983', '1984', '1986', '1988', '1990', '1993', '1994', '1995', '1998', '6574', '6575', '6589', '6611', '6623', '6624', '6635', '6637', '6642', '6643', '6649', '6651', '6661', '6673', '6676', '6687', '6701', '6709', '6718', '6722', '6728', '6748', '6765', '6767', '6772', '6784', '6791', '6815', '6816', '6817', '6823', '6832', '6839', '6843', '6848', '6851', '6882', '6902', '6921', '6922', '6924', '6938', '6940', '6946', '6950', '6955', '6965', '6966', '6967', '6970', '6980', '6981', '6984', '6987', '6988', '6990'
        ];

        // Known image URLs for specific colors
        $knownImageUrls = [
            '672' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXBX0xiM1UgtK-VON0mX4RyY95pxbVX4NGs6kgjP_Q8uGPQH_aXAjP9hVhbnxPz5cxvdQirgYaU8TDltXXRTR6jIkQRACZxHRekTn_1ZZUEswEdoZKqgwDY-Dur1WXyWVXIDjnIBVXiuMoSbp3M6kU=s120-w120-h59',
            '1500' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7hZX5ZjXkHKhy7zM9IbiYkcRJoCwqRsa74NDzcgF_6423dfDSgdUUd79f-hOKd_kNIlPJIm-eImjP1Xjr3xGLE-yMYR8pzb7pIPa7BL9IXUC7afIm-IEfa2ghLZg102CHgcp9JlBQ1wxKUC5Dpsg=s120-w120-h59',
            '1508' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV-CTH7mM5nCPZ6XDnYyW867sP0P2Nb2ptEvHLw-DJYeRg-3hf5THgIdNdyxatJKOLc4EDd7fNRLxJ6tDcz6H8mCIwbnnyVkcxy5ezoRSyx3SmkIOQb5kJVgd_wDm_KS3a_AuqmgVtyM4HeSCvr9c0=s120-w120-h59',
            '1509' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVYZwzd4YoN88fmjXLlXrufoHFDV5pc1dFgHo3LJIZPH8FpoXrZ3pMLAGvQerQ1QjEjyPmUY0nd1arpnCh0xxG9rGAiLwr6EbvGmA9pZzs_qDHovgIO6RuW0gjRQGig_NDh5rNznrbztfzOb6jB6bQ=s120-w120-h59',
            '1510' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVRu58kyL62p03wsYBOBmwFnOGfE_HvLgCYPo-b8GYQVyuqBzO9bLjs-SiwkAQPAk8LMLwRqybpwfl1CSZH-TPY_Xd4-T703WdQcAyZN9TRPHA3sj4eBQBMgkjYBm3LZD8mjHuWToJDA5FCOGe5iu8=s120-w120-h59',
            '1511' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVsSimcB56PXklgorZfoTemTadv8yYZgqTFIZoDtoMMuSLcs2TrufsexK8UTtddufeL4yha3CM6KQ2hEu0a4YHCqK53iMAnR-9qWOfab4QZxl4GV4bd6bYybSzEeiV9Yxm16O_SLDMsWrw-IChPCQ=s120-w120-h59',
            '1512' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXFUZF6xCdwiGayapm7WwZZUKD9UssWBxJOGP_f4vYD_YZ5sg8CUV9RcwKfvqipN8UttKK5M2F9cgmJYdqq6VCpG4MfSnucaSk5CDQzP12SMl9p7JulwkhKFftfVoiSmTSUQ--_Lxchv4tb0CDrGwU=s120-w120-h59',
            '1513' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59',
            '1514' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUN9XTm9pkb5SyngE3d0qAIgbnxrZ6PkVuCOk8Dl...(27814 chars omitted)...fO3eb9WDN8NsRVoQqxY3zy-13ACv4MsemDYa1nqiS6MSHhRAxTiBtKoqtVhj1J851pu7pqx44vGw9g=s120-w120-h59',
            '1515' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59',
            '1516' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWTwXlxtm9TljF7pJp8h5_PvJXAPnHAnol5Rlk-l2dtIDcPqwj4pX1MUpIlD8nZZJf8YjoQbnb8pArrYn6KX8EUEoZ_pBdkv5YLQA6JVF9clDhxufyTMpU2KmkS0oj3MWsSvEPPkSRkONLdZT1b3w=s120-w120-h59',
            '1754' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xW09-gYr5xzIEI0TcaxUKkteUsnNV6kejy4BxYShE8-oWua1QMOVMTIGmRzZidXdMR7zItjOD9gnFeSNfLjKjhi5baQD3xun1snkpsdNNXSdnfJ8c_a4JkOFMN6cNVNzVIY8OZ4Eexc_ABssA-jegg=s120-w120-h59',
            '1758' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xXtuSlplS3aHjcGjRhSGqvEgRCjjx18mixKyyn-YD0Ll5vVXuS-1U6cOUTYsUj_bfEpDJv9EukH72yzFT00R-pxA-gOTQcg7Xz7sQPXC2iLtfjI9wrickCsFVEjaCt7lnn2eVYF2dd--z2Ed09tQ6A=s120-w120-h59',
            '1767' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWCKNNHvb2CDXflmZ89xMO-EFMHCwfn0Jzt6aExAZqSX4dpb-8kKb3L7DUJZoAg2taGE5gPYEqR_E4NhyGD5IhLxeJEwTI9oxSY1eoND4qhEa6yJljA2l54US53YqXVw7-81xWFWY4FGUL2FcDzpk0=s120-w120-h59',
            '1768' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xWH6nt3Ipm0bkvh3QTiLudTGqNA6CwRJ1ExE4kjmJb4KQdJAzU6g7ZVw5urqn41LLRk4Mf7VrsB2YEHAn-bI_9er4tpskNhcmC9zr3MJUUYyZ-0FTr-bA09r-VmVr80L96fw7lybGqTqQKT_khngg=s120-w120-h59',
            '1769' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV7oDreG45DK4PdOdbivfp2GUXqaTnrPuCepWLIAvQm42UXFGMdI5mJdruti4GoFVzpPsg-Dee7jDyOvkWqu_vtCCDC0CQHtBjQ1Wcfs6jcpFmE7DkbFNH6UEjoAni4AXMHq6wK0dtN_gI7278TY6Y=s120-w120-h59',
            '1770' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVBMdLpcgeNYQJg-ScvuBqe3H8wphMO67frORdjj01NlEupxk64ZmZGgPI6tJkW3Jg2IVDBj3jWqlgLJ1OSX6fw69gzvRPLEYHLnOBFvOVifrshoDUqBahKgclFzyRkerZV89PVmQlRBV7lxiDv_Q=s120-w120-h59',
            '1777' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xUG27BsIsNekbVCN9J-UfT_2mQBGiRYJELje6WgFoV1PUHchnJSQ40OWhH_bqXuWh4DFrm1Gg5AwzSK_hDTx3m2QgUKI78FqMU5won5wWIqyRgl1t-Nhsw4b-NKoLALAwAXHATVRbmOVqPmF9bEGQ=s120-w120-h59',
            '1779' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xVcqVJYG9OMBgYo8NLmTjYZzbb6ZiU27vC-UYwJwL1H_f9GIfsNyKAeFDJtu2gkF_vrUSE-G9NIzflydKRvcpKEpjGK-neHAEnISNGqxhnt6o6u3A7clX3dv39m1zt34sRD7YaHyBpamuwPD_ZXnLo=s120-w120-h59',
            '1781' => 'https://lh3.googleusercontent.com/docsubipk/AP9E6xV_prtpAP5QHygKhBseu789a7Vbtn3pxnySWaDjCYtYVymZ0HYOGYNc_HPtXGeqX6TpEHY9StzqljxNNRuWNWsxoTWfSuEArgj7KuLX47bfl_GJKi423qfpW1es7DMnJbU1GZc-VeCGbv4eexcRDGo=s120-w120-h59',
        ];

        // Import all thread colors
        foreach ($threadColors as $colorCode) {
            $imageUrl = $knownImageUrls[$colorCode] ?? 'https://via.placeholder.com/120x59?text=' . $colorCode;
            
            ThreadColor::create([
                'color_name' => $colorCode,
                'color_code' => $colorCode,
                'image_url' => $imageUrl,
            ]);
        }
    }
}