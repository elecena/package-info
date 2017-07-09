<?php

use Elecena\Utils\PackageInfo;

/**
 * Set of integration tests for ParametersParser class
 */
class ParametersParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @param string $desc
	 * @param string $expected
	 *
	 * @dataProvider getPackageProvider
	 */
	public function testGetPackage($desc, $expected) {
		$this->assertEquals($expected, PackageInfo::getPackage($desc), "{$desc} should be detected as {$expected}");
	}

	/**
	 * @return array
	 */
	public function getPackageProvider() {
		return [
			[ 'foo', false ],

			[ 'Tranzystor: NPN; bipolarny; 80V; 7A; 40W; TO220', 'TO-220' ],
			[ 'Tranzystor: NPN; bipolarny; 80V; 7A; 40W; to220', 'TO-220' ],
			[ 'Tranzystor: NPN; bipolarny; 80V; 7A; 40W; to-220', 'TO-220' ],
			[ 'Obudowa: TO220SG=0.6mm', 'TO-220SG' ],

			[ 'BT136 (TO-220F)', 'TO-220F' ],
			[ '2SA1538 Tranzystor PNP 120V 0,2A 8W 400MHz SOT186', 'TO-220F' ],
			[ 'TK15A50 500V 15A 0,24R n-mosfet SC67', 'TO-220F' ],
			[ 'MOSFET,N CHANNEL,600V,3.7A,SC-67', 'TO-220F' ],

			[ 'Tranzystor: NPN; bipolarny; 60V; 600mA; 350mW; TO92', 'TO-92' ],
			[ 'Tranzystor: NPN; bipolarny; 60V; 600mA; 350mW; TO-92', 'TO-92' ],

			[ 'Tranzystor: NPN; TO39; bipolarny; 75V; 500mA; 800mW', 'TO-39' ],
			[ 'TO39; bipolarny; 75V; 500mA; 800mW', 'TO-39' ],

			[ 'Tranzystor BC107 NPN 45V-100mA-300mW obudowa:TO-18', 'TO-18' ],
			[ 'BC107B TO-18', 'TO-18' ],
			[ 'BC107 TO-18 NPN 100mA 45V 300mW', 'TO-18' ],

			[ 'BTA26-600BRG TRIAC 600V 25A TOP3', 'TOP3' ],
			[ '2SK2370 NEC MOSFET 500V 20A TOP-3 TRANSISTOR', 'TOP3' ],

			[ 'MOSFET 2N-CH 1200V 34A SP4', 'SP4' ],
			[ 'MOSFET 6N-CH 600V 116A SP6-P', 'SP6-P' ],

			[ 'IGBT 1200V 35A 260W SOT-227', 'SOT-227' ],
			[ 'MOD THYRISTOR SGL 1200V SOT-227B', 'SOT-227B' ],
			[ 'MOD THYRISTOR SGL 1200V SOT-227B', 'SOT-227B' ],
			[ 'ISOTOP®', 'SOT-227' ],
			[ 'SOT-227-4 N-Channel 600 V MOSFET', 'SOT-227' ],

			[ 'Pamięć; EEPROM; I2C; 128x8bit; 1,8÷5,5V; 400kHz; DFN8', 'DFN8' ],

			[ 'Pamięć; EEPROM; UNI/O; 256x8bit; 1,8÷5,5V; 100kHz; MSOP8', 'MSOP8' ],
			[ 'Mikrokontroler ARM; Flash:200kB; SRAM:16kB; 32MHz; PG-TSSOP-38', 'TSSOP38' ],
			[ 'Pamięć; EEPROM; I2C; 128x8bit; 1,7÷5,5V; 400kHz; TSSOP8', 'TSSOP8' ],
			[ 'Nadajnik-odbiornik linii; RS232 / V.28; 4,5÷5,5VDC; SSOP20', 'SSOP20' ],

			[ 'Odbiornik linii; RS232; L.odb:4; 4,5÷5,5VDC; SO14; 0÷75°C', 'SO14' ],
			[ 'Nadajnik-odbiornik linii; RS232,full duplex; 4,5÷5,5VDC; SO16', 'SO16' ],
			[ 'Odbiornik linii; RS422,RS423; L.odb:4; 4,5÷5,5VDC; SO16; -40÷85°C', 'SO16' ],
			[ 'Nadajnik-odbiornik linii; RS422 / RS485; 5VDC; SO8-W', 'SO8-W' ],
			[ 'L6386AD (L6386 AD) SO-14', 'SO14' ],
			[ 'L6386 SMD (SOP-14)', 'SOP14' ],

			[ 'ATTINY10-TS8R Mikrokontroler AVR; SRAM:32B; Flash:1kB; SOT23-6; Uzas:1,8÷5,5V', 'SOT23-6' ],
			[ 'MCP3421A0T-E/CH - 18-bitowy przetwornik A/D SMD SOT23-6 PBF', 'SOT23-6' ],

			[ 'DIODE AVALANCHE 200V 2A SOD57', 'SOD57' ],
			[ 'BYD37M PRODUCENT / MANUFACTURER: PHILIPS OBUDOWA / PACKAGE: SOD-87 FAST SOFT-RECOVERY CONTROLLED', 'SOD87' ],

			[ 'Nadajnik linii; RS422,RS485; L.nad:4; DIP16; 4,75÷5,25VDC; 0÷70°C', 'DIP16' ],
			[ 'Nadajnik-odbiornik linii; RS422 / RS485; 5VDC; DIP8', 'DIP8' ],
			[ 'Pamięć; EEPROM; UNI/O; 128x8bit; 1,8÷5,5V; 100kHz; DIP-8', 'DIP8' ],

			[ 'Układ scalony MAX7219CNG [DIL-22]', 'DIL22' ],
			[ 'Mikrokontroler AVR-RISC Atmel ATTINY26-16PU, DIL-20, 0 - 16 MHz Flash: 2 kB, RAM: 128', 'DIL20' ],

			[ 'Mikrokontroler ARM; Flash:64kB; SRAM:16kB; 48MHz; PG-LQFP-64', 'LQFP64' ],

			[ 'Kwarc, rezonator kwarcowy 49,95416MHz [HC-49U]', 'HC49U' ],
			[ 'Rezonator: kwarcowy; 12MHz; ±30ppm; 30pF; THT; HC49-S', 'HC49-S' ],
			[ 'Rezonator: kwarcowy; 12MHz; ±30ppm; 30pF; THT; HC49-S', 'HC49-S' ],
			[ 'Rezonator: kwarcowy; 12,288MHz; ±30ppm; 16pF÷30pF; THT; HC49/U', 'HC49' ],
			[ 'Rezonator kwarcowy 16MHz - HC49 - niski', 'HC49' ],
			[ 'HC49/4HSMX 30/50/20/18 30 MHZ - Kryształ, 30 MHz, SMD, 11.4mm x 4.9mm, 50 ppm, 18 pF, 30 ppm, Seria HC-49/4HSMX', 'HC49' ],

			[ 'STA540SA (STA540 SA) Clipwatt-19', 'CLIPWATT-19' ],
			[ 'STA540SAN (STA540 SAN) Clipwatt-15', 'CLIPWATT-15' ],
			[ 'STA540SA CLIPWATT-19', 'CLIPWATT-19' ],
			[ 'STA540SA (STA540 SA) 4 x 10-watt dual/quad power amplifier Clipwatt 19', 'CLIPWATT-19' ],
			[ 'STV-9556 CLIPWATT-11 RGB AMPLIFIER UKŁAD', 'CLIPWATT-11' ],
			[ 'TDA-7269-SA CLIPWATT-11 UKŁAD', 'CLIPWATT-11' ],

			[ 'ATMEGA64-16AU Obudowa:TQFP64', 'TQFP64' ],
			[ '100-TQFP (14x14)', 'TQFP100' ],
			[ '128-TQFP (10x10)', 'TQFP128' ],
			[ '144-TQFP', 'TQFP144' ],
			[ '32-TQFP', 'TQFP32' ],
			[ '44-TQFP', 'TQFP44' ],
			[ '48-HTQFP', 'HTQFP48' ],
			[ '48-TQFP', 'TQFP48' ],
			[ '64-HTQFP', 'HTQFP64' ],
			[ '64-TQFP', 'TQFP64' ],
			[ '80-TQFP', 'TQFP80' ],

			// no package data
			[ 'Tranzystor: NPN; STO39; bipolarny; 75V; 500mA; 800mW', false ],
			[ 'Tranzystor: NPN; TO3954; bipolarny; 75V; 500mA; 800mW', false ],
			[ 'Tranzystor: NPN; TO39A; bipolarny; 75V; 500mA; 800mW', false ],
			[ 'Tranzystor: NPN; DIP220; bipolarny; 75V; 500mA; 800mW', false ],

			// igbt-power normalization
			# [ 'TO-261-4', '-' ],
			# [ 'TO-226-3', '-' ],
			[ 'TO-126', 'TO-126' ],
			[ '16-SOIC', 'SOIC16' ],
			[ '16-TSSOP', 'TSSOP16' ],
			[ '144-LQFP', 'LQFP144' ],
			[ '64-LQFP', 'LQFP64' ],

			// normalize packages
			[ '64-LQFP (10x10)', 'LQFP64' ], // https://elecena.pl/product/7840023/atsam3n1bb-aur (DigiKey)
			[ '28-QFN (6x6)', 'QFN28' ],
			[ '20-TSSOP', 'TSSOP20' ],
			[ '144-LQFP (20x20)', 'LQFP144' ],

			// https://en.wikipedia.org/wiki/DO-204
			[ 'DO-204AL (DO-41)', 'DO-41' ],
			[ 'DO-35 (DO-204AH)', 'DO-35' ],
			[ 'DO-7 (DO-204AA)', 'DO-7' ], // https://elecena.pl/product/6710195/1n747a (Digi-Key)

			[ 'DO-204AA', 'DO-7' ],
			[ 'DO-204AH', 'DO-35' ],
			[ 'SOD27', 'DO-35' ],
			[ 'DO-204AL', 'DO-41' ],
			[ 'SOD66', 'DO-41' ],
		];
	}
}
