<?php

use Illuminate\Database\Seeder;
use App\Equipment;
use App\CatalogHour;

class SetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contador_consola = 0;
        $key_css = array('', 'XMLID_1703_','XMLID_1702_','XMLID_1701_', 'XMLID_1700_', 'XMLID_1697_',
                        'XMLID_1698_','XMLID_1699_','XMLID_210_','XMLID_326_','XMLID_327_',
                        'XMLID_1689_','XMLID_1688_','XMLID_1687_','XMLID_1686_','XMLID_1685_',
                        'XMLID_1684_','XMLID_1683_','XMLID_1681_','XMLID_328_','XMLID_1682_',
                        'XMLID_1696_','XMLID_1695_','XMLID_1690_','XMLID_1691_','XMLID_1692_',
                        'XMLID_1693_','XMLID_1694_','XMLID_1707_','XMLID_1706_','XMLID_1705_',
                        'XMLID_1704_'
                        );
        $circle_number = array('','XMLID_162_','XMLID_207_','XMLID_1709_','XMLID_1715_',
                            'XMLID_1718_','XMLID_1721_','XMLID_1724_','XMLID_1770_',
                            'XMLID_1767_','XMLID_1764_','XMLID_1755_','XMLID_1752_',
                            'XMLID_1749_','XMLID_1746_','XMLID_1743_','XMLID_1740_',
                            'XMLID_1737_','XMLID_1734_','XMLID_1731_','XMLID_1728_',
                            'XMLID_1758_','XMLID_1761_','XMLID_203_','XMLID_200_',
                            'XMLID_197_','XMLID_173_','XMLID_167_',
                            'XMLID_209_','XMLID_541_','XMLID_550_','XMLID_1672_'

                            );
        $clases = array('','XMLID_155_,XMLID_151_,XMLID_150_,XMLID_149_,XMLID_148_,XMLID_1_',
                        'XMLID_154_,XMLID_146_,XMLID_145_,XMLID_144_,XMLID_143_,XMLID_206_',
                        'XMLID_153_,XMLID_141_,XMLID_140_,XMLID_139_,XMLID_138_,XMLID_1708_',
                        'XMLID_152_,XMLID_136_,XMLID_135_,XMLID_21_,XMLID_20_,XMLID_1714_ ',
                        'XMLID_128_,XMLID_126_,XMLID_125_,XMLID_124_,XMLID_123_,XMLID_1717_',
                        'XMLID_127_,XMLID_121_,XMLID_120_,XMLID_119_,XMLID_23_,XMLID_1720_',
                        'XMLID_134_,XMLID_133_,XMLID_132_,XMLID_131_,XMLID_130_,XMLID_1793_',
                        'XMLID_195_,XMLID_193_,XMLID_192_,XMLID_191_,XMLID_190_,XMLID_1769_',
                        'XMLID_194_,XMLID_188_,XMLID_187_,XMLID_186_,XMLID_185_,XMLID_1766_',
                        'XMLID_183_,XMLID_182_,XMLID_181_,XMLID_180_,XMLID_179_,XMLID_1763_',
                        'XMLID_106_,XMLID_101_,XMLID_100_,XMLID_99_,XMLID_98_,XMLID_1754_',
                        'XMLID_105_,XMLID_96_,XMLID_95_,XMLID_94_,XMLID_93_,XMLID_1751_',
                        'XMLID_104_,XMLID_91_,XMLID_90_,XMLID_89_,XMLID_88_,XMLID_1748_',
                        'XMLID_103_,XMLID_36_,XMLID_18_,XMLID_17_,XMLID_30_,XMLID_1745_',
                        'XMLID_102_,XMLID_14_,XMLID_13_,XMLID_12_,XMLID_15_,XMLID_1742_',
                        'XMLID_86_,XMLID_81_,XMLID_80_,XMLID_70_,XMLID_78_,XMLID_1739_',
                        'XMLID_85_,XMLID_76_,XMLID_75_,XMLID_74_,XMLID_73_,XMLID_1736_',
                        'XMLID_84_,XMLID_71_,XMLID_70_,XMLID_69_,XMLID_68_,XMLID_1736_',
                        'XMLID_83_,XMLID_66_,XMLID_65_,XMLID_64_,XMLID_63_,XMLID_1730_',
                        'XMLID_82_,XMLID_61_,XMLID_60_,XMLID_59_,XMLID_58_,XMLID_1727_',
                        'XMLID_117_,XMLID_111_,XMLID_110_,XMLID_109_,XMLID_108_,XMLID_1757_',
                        'XMLID_118_,XMLID_116_,XMLID_115_,XMLID_114_,XMLID_113_,XMLID_1760_',
                        'XMLID_8_,XMLID_5_,XMLID_24_,XMLID_34_,XMLID_25_,XMLID_202_',
                        'XMLID_7_,XMLID_41_,XMLID_40_,XMLID_39_,XMLID_38_,XMLID_199_',
                        'XMLID_6_,XMLID_46_,XMLID_45_,XMLID_44_,XMLID_43_,XMLID_196_',
                        'XMLID_4_,XMLID_51_,XMLID_50_,XMLID_49_,XMLID_48_,XMLID_172_',
                        'XMLID_10_,XMLID_56_,XMLID_55_,XMLID_54_,XMLID_53_,XMLID_1794_',
                        'XMLID_26_,XMLID_161_,XMLID_160_,XMLID_1725_',
                        'XMLID_29_,XMLID_158_,XMLID_157_,XMLID_1772_',
                        'XMLID_28_,XMLID_33_,XMLID_32_,XMLID_1792_',
                        'XMLID_27_,XMLID_171_,XMLID_170_,XMLID_1791_'
                        );
        $nombre_equipo = array('','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'
        ,'24','25','26','27', '1','2','3','4','5','6','7','8','9','10'
        );
        for ($i=1; $i <=31 ; $i++) { 
            $equipments = new Equipment;
            
            if($i>27){
                $equipments->tipo_equipo='1';
            }else{
                $equipments->tipo_equipo='2';
            }

            $equipments->numero_equipo=$nombre_equipo[$i];
            $equipments->nombre_equipo="Equipo ".$i;
            $equipments->class=$clases[$i];
            $equipments->key_css=$key_css[$i];
            $equipments->circle_number=$circle_number[$i];

            $equipments->save();
        }

        /* 
            insertar en la tabla de horarios
            De 9 am a 12 am de lunes a jueves
            Viernes y sábado de 9 a 3 am
            Domingos de 12 a 10 pm
        */
        $catalog = new CatalogHour;
        $catalog->dias= '1,2,3,4';
        $catalog->horario='09:00am,09:30am,10:00am,10:30am,11:00am,11:30am,12:00pm,12:30pm,01:00pm,01:30pm,02:00pm,02:30pm,03:00pm,03:30pm,04:00pm,04:30pm,05:00pm,05:30pm,06:00pm,06:30pm,07:00pm,07:30pm,08:00pm,08:30pm,09:00pm,9:30pm,10:00pm';
        $catalog->save();
        
        $catalog = new CatalogHour;
        $catalog->dias='5,6';
        $catalog->horario='09:00am,09:30am,10:00am,10:30am,11:00am,11:30am,12:00pm,12:30pm,01:00pm,01:30pm,02:00pm,02:30pm,03:00pm,03:30pm,04:00pm,04:30pm,05:00pm,05:30pm,06:00pm,06:30pm,07:00pm,07:30pm,08:00pm,08:30pm,09:00pm,10:00pm,10:30pm,11:00pm,11:30pm,12:00am,01:00am,01:30am,02:00am';
        $catalog->save();
        
        $catalog = new CatalogHour;
        $catalog->dias='0';
        $catalog->horario='12:00pm,12:30pm,01:00pm,01:30pm,02:00pm,02:30pm,03:00pm,03:30pm,04:00pm,04:30pm,05:00pm,05:30pm,06:00pm,06:30pm,07:00pm,07:30pm,08:00pm,08:30pm,09:00pm,09:30pm,10:00pm,10:30pm,11:00pm,11:30pm,12:00am,01:00am,01:30am,02:00am,02:30am,03:00am';
        $catalog->save();

       
    }
}
