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
			[ 'Stabilizator napięcia; nieregulowany; 5V; 0,5A; TO220AB; THT', 'TO-220AB' ],
			[ 'TRIAC 800V 10A TO-220FP', 'TO-220FP' ],
			[ '7806CV 1,5A TO220(Single Gauge=0.6mm) * Liczba w opakowaniu:50 * Obudowa:TO220SG=0.6mm', 'TO-220SG' ],
			[ 'DIODE ARRAY SCHOTTKY 45V ITO220', 'ITO-220' ],

			[ 'Tranzystor PNP 100 V 25 A 3 MHz HFE 10 TO-247 TIP36C 3-Pin', 'TO-247' ],
			[ 'BU626A TO-3', 'TO-247' ],
			[ 'Dioda FYA3010DNTU 30A 100V TO-3P 3-Pin', 'TO-247' ],
			[ 'IGBT 600V 24A 54W TO-3PF', 'TO-3PF' ],

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
			[ 'OP-AMP, DIFFERENTIAL, 350MHZ, 1200V\/us, MSOP-8', 'MSOP8' ],
			[ 'Monitor napięcia, 3.5V-100Vin, MSOP-16', 'MSOP16' ],
			[ '10-MSOP-EP', 'MSOP10-EP' ],
			[ '10-MSOP-PowerPad', 'MSOP10-POWERPAD' ],
			[ 'IC REG LDO 3.3V 1A 8-HTSOP-J', 'HTSOP8-J' ],
			[ 'Operational Amplifiers - Op Amps TLVx314 3-MHz, Low-Power, Low-Noise, RRIO, CMOS Operational Amplifiers 8-VSSOP -40 to 125', 'VSSOP8' ],
			[ 'IC DIRECT RAMBUS CLK GEN 24-QSOP', 'QSOP24' ],

			[ 'ATTINY10-TS8R Mikrokontroler AVR; SRAM:32B; Flash:1kB; SOT23-6; Uzas:1,8÷5,5V', 'SOT23-6' ],
			[ 'MCP3421A0T-E/CH - 18-bitowy przetwornik A/D SMD SOT23-6 PBF', 'SOT23-6' ],
			[ 'IC LDO 300MA LOW IQ TSOT-23-5', 'TSOT23-5' ],
			[ 'Układ napięcia odniesienia, precyzyjny, szeregowy - stały, seria LT6656, 5V, TSOT-23-6', 'TSOT23-6' ],
			[ 'DC-DC Switching Buck Regulator, Adjustable, 4.5 V-18 Vin, 0.6 V-5 V/4 A out, 500 kHz, TSOT-23-8', 'TSOT23-8' ],
			[ 'IC OPAMP VFB 90MHZ RRO TSOT-5', 'TSOT23-5' ],

			[ 'TRANS NPN 80V 1A SOT-89', 'SOT89-3' ],
			[ 'MOSFET, N CHANNEL, 300V, 0.2A, SOT-89-3' ,'SOT89-3' ],
			[ 'RF Amplifier IC, 21 dB Gain / 4.5 dB Noise, DC to 4 GHz, 5 V supply, SOT-89-4', 'SOT89-3' ],
			[ 'IC LDO 0.2A LOW DROPOUT SOT-89-5', 'SOT89-5' ],

			[ 'BSP52E6327 INFIN 0205 SOT223', 'SOT223' ],
			[ 'PNP high-voltage low VCEsat Breakthrough In Small Signal (BISS) transistor in a SOT223 (SC-73) medium power', 'SOT223' ],
			[ 'Tranzystor NPN 350 V 100 mA 70 MHz HFE 40 TO-261AA BSP19AT1G 3-Pin', 'SOT223' ],
			[ 'Stabilizator napięcia; nieregulowany; 6V; 3,3V; 500mA; SOT223-5', 'SOT223-5' ],
			[ 'LDO VOLTAGE REGULATOR, 1.8V, 0.4A, SOT-223-6, FULL REEL', 'SOT223-6' ],
			[ 'ZXMHC6A07T8TA N & P CHANNEL MOSFET, -60V, SM-8', 'SOT223-8' ],
			[ 'MOSFET 2N-CH 60V 2A SOT-223-8', 'SOT223-8' ],

			[ 'DIODE AVALANCHE 200V 2A SOD57', 'SOD57' ],
			[ 'BYD37M PRODUCENT / MANUFACTURER: PHILIPS OBUDOWA / PACKAGE: SOD-87 FAST SOFT-RECOVERY CONTROLLED', 'SOD87' ],

			[ 'Nadajnik linii; RS422,RS485; L.nad:4; DIP16; 4,75÷5,25VDC; 0÷70°C', 'DIP16' ],
			[ 'Nadajnik-odbiornik linii; RS422 / RS485; 5VDC; DIP8', 'DIP8' ],
			[ 'Pamięć; EEPROM; UNI/O; 128x8bit; 1,8÷5,5V; 100kHz; DIP-8', 'DIP8' ],
			[ 'Transoptor 4N37 z tranzystorem 1-kanałowy DC DIP 6 Vishay', 'DIP6' ],
			[ 'IC VREF SERIES PREC 5V 8-CERDIP', 'CERDIP8' ],
			[ 'IC FPS POWER SWITCH 650V 8-MDIP', 'MDIP8' ],
			[ 'LNK6769V IC LINKSWITCH 39W 54W 12-EDIP', 'EDIP12' ],
			[ 'TODX283 PRODUCENT / MANUFACTURER: TOSHIBA OBUDOWA / PACKAGE: DIP-10 OPTOELECTRONIC', 'DIP10' ],
			[ 'FOO DIP-11', false ],

			[ 'PIC16C55A-04/SP 8-bit Microcontrollers - MCU .75KB 24 RAM 20 I/O 4MHz SPDIP-28', 'SPDIP28' ],
			[ 'PIC32MX230F256B-I/SP MCU, 32BIT, PIC32MX, 50MHZ, SDIP-28', 'SDIP28' ],
			[ 'M50780SP PRODUCENT / MANUFACTURER: MITSUBISHI OBUDOWA / PACKAGE: SDIP-40', 'SDIP40' ],
			[ 'FOO SDIP-16', false ],

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

			[ 'Transceiver, 74LVC8T245, 1.2 V do 5.5 V, DHVQFN-24', 'DHVQFN24' ],
			[ 'MAX3840ETJ Crosspoint Switch, Dual Interface, 2x2 Array, +3 V to +3.6 V, TQFN-32', 'TQFN32' ],
			[ 'MAX8662ETM IC POWER MANAGE 48-TQFN-EP', 'TQFN48-EP' ],
			[ 'LMK03328RHST "Clock Generator, 300 MHz, 3.135 V to 3.465 V, 8 Outputs, WQFN-48', 'WQFN48' ],
			[ 'DSPIC33EP16GS202-I/MX DSC, 16BIT, 16KB, 140MHZ, 3.6V, UQFN-28', 'UQFN28' ],

			# N-pin handling
			# [ 'PIC24HJ64GP502-I/MM 16 bit PIC 40MIPS 64 kB Flash 8 kB RAM 28-Pin QFN-S', 'QFN28-S' ], # TODO
			# [ 'Wzmacniacz operacyjny LF411CN/NOPB 4MHz MDIP, 8-Pin', 'MDIP8' ],

			[ 'AD8353ACPZ IC, AMP, RF\/IF, SMD, LFCSP-8, 8353', 'LFCSP8' ],
			[ 'ADP2503ACPZ-3.3-R7 DC/DC CONV, BUCK-BOOST, 2.5MHZ, LFCSP-10', 'LFCSP10' ],
			[ 'ADXL325BCPZ Small, Low Power, 3-Axis +/-5 g Accelerometer, 16-LFCSP, Analog Devices, RoHS', 'LFCSP16' ],
			[ '24-LFCSP-WQ (4x4)', 'LFCSP24-WQ' ],

			[ '32-LQFP', 'LQFP32' ],
			[ '44-LQFP', 'LQFP44' ],
			[ '48-LQFP', 'LQFP48' ],
			[ '52-LQFP', 'LQFP52' ],
			[ '64-LQFP', 'LQFP64' ],
			[ '80-LQFP', 'LQFP80' ],
			[ '100-LQFP', 'LQFP100' ],
			[ '112-LQFP', 'LQFP112' ],
			[ '120-LQFP', 'LQFP120' ],
			[ '128-LQFP', 'LQFP128' ],
			[ '144-LQFP', 'LQFP144' ],
			[ '176-LQFP', 'LQFP176' ],

			[ '32-VFQFPN (5x5)', 'VFQFPN32' ],
			[ '44-MQFP (10x10)', 'MQFP44' ],

			[ 'S29GL128P11FFIV10 MEMORY, FLASH NOR, 128MBIT, FBGA-64', 'FBGA64' ],
			[ 'SDRAM, DDR3, 128M x 16bit, 1.25 ns, FBGA-96', 'FBGA96' ],
			[ 'XQ7K410T-1RF900I IC FPGA KINTEX-7 900-FBGA', 'FBGA900', ],
			[ 'XQ2VP70-5FF1704N IC FPGA VIRTEX-II PRO 1704-FBGA', 'FBGA1704' ],
			[ 'MPC8360VVAGDGA Microprocessor, MPC8360 Series, 400 MHz, TBGA-191', 'TBGA191' ],
			[ 'Microprocessor, PowerQUICC II Series, 450 MHz, 1.45 V to 1.6 V, TBGA-480', 'TBGA480' ],
			[ 'IC SDRAM 8GBIT 1.067GHZ FBGA', false ],

			[ 'AB-RTCMC-32.768KHZ-AIGZ-S7-T IC RTC CLK/CALENDAR I2C 8-CLCC', 'CLCC8' ],
			[ 'CY25701FLXCT IC OSC XTAL PROG 4-CLCC', 'CLCC4' ],
			[ 'DS4100H Timer, Oscillator & Pulse Generator IC, Low Jitter HCSL, 100 MHz, 3.135 V to 3.465 V, LCCC-10', 'LCCC10' ],
			[ 'DAC7725 PLCC-28', 'PLCC28' ],

			// DigiKey normalization
			[ '8-SON (A) (2.9x2.8)', 'SON8' ],
			[ '6-SON (1.45x1)', 'SON6' ],
			[ '6-XSON, SOT886 (1.45x1)', 'XSON6' ],
			[ '8-XSON, SOT996-2 (2x3)', 'XSON8' ],
			[ '10-WSON (3x3)', 'WSON10' ],
			[ '10-VSON (3x3)', 'VSON10' ],
			[ '4-X2SON (1x1)', 'X2SON4' ],
			[ 'PG-TDSON-8', 'PGTDSON-8' ],

			[ 'DO-200AA, R62', 'DO-200AA' ],
			[ 'DO-200AB, B-PUK', 'DO-200AB' ],
			[ 'DO-205AB, DO-9', 'DO-205AB' ],
			[ 'DO-213AB (MELF, LL41)', 'DO-213AB' ],
			[ 'DO-214AB, (SMC)', 'DO-214AB' ],
			#[ 'LFPAK56, Power-SO8', '' ],

			[ 'Tranzystor P-MOSFET 55V 18A DPAK TO-252', 'TO-252' ],
			[ 'TO-252, (D-Pak)', 'TO-252' ],
			[ 'D-Pak', 'TO-252' ],
			[ 'DPAK', 'TO-252' ],
			[ 'CSD19506KTT MOSFET N-CH 80V 200A DDPAK-3', 'TO-263' ],
			[ 'Stabilizator napięcia LDO LM1085IS-5.0/NOPB 3A 5 V 2,6 → 25 Vin TO-263 3-Pin', 'TO-263' ],
			[ 'IC REG LDO 5V 0.15A DDPAK', 'TO-263' ],
			[ 'DIODE, 10A, 50V, TO-263AB', 'TO-263AB' ],
			[ 'STEP-DOWN REGULATOR, TO-263-5', 'TO-263-5' ],

			[ '0603/SOD-523F', 'SOD523F' ],
			[ '1005/SOD-323F', 'SOD323F' ],
			[ 'SOD323F (SC-90)', 'SOD323F' ],
			[ 'BAS21J Dioda przełączająca BAS21J, 2-Pin, SMD, 300V, 250mA, SC-90' , 'SOD323F' ],

			[ 'PWM CONTROLLER, 1KHZ, 26.5V, UMAX-8', 'UMAX8' ],
			[ 'MAX5703AUB DAC, 8BIT, UMAX-10', 'UMAX10' ],
			[ 'IC POT NV 128POS HV 10-USOP', 'USOP10' ],
			[ 'DS1080LU CLOCK GENERATOR, 134MHZ, USOP-8', 'USOP8' ],

			[ 'IC SUPERVISOR 1.8V USP-3', 'USP3' ],
			[ 'IC REG LDO 2.85V 0.2A USP4', 'USP4' ],
			[ '4-USP (1.2x1.6)', 'USP4' ],
			[ 'IC REG BUCK 1.5V 0.6A SYNC USP-6', 'USP6' ],
			[ 'IC VOLT DET TIME DLY 2.5V 4-USPN', 'USPN4' ],
			[ 'IC REG LDO 2.5V 0.3A USPQ-4B03', 'USPQ4B03' ],
			[ 'IC REG BST ADJ 1.2A SYNC USP10B', 'USP10B' ],

			[ 'PMR290UNE N-channel enhancement mode Field-Effect Transistor (FET) in a small SOT416 (SC-75) Surface-Mounted Device (SMD) plastic package using Trench MOSFET', 'SOT416' ],
			[ 'DAN222T1G Dioda DAN222G 100mA 80V 4ns SOT-416 3-Pin', 'SOT416' ],
			[ 'TRANS PREBIAS NPN 150MW SOT523', 'SOT416' ],
			[ '2SA2018TL Tranzystor bipolarny PNP 12 V 500 mA 100 MHz HFE 270 SC-75A 2SA2018TL 3-Pin' ,'SOT416' ],
			[ 'BC847BTT1G BIPOLAR TRANSISTOR, NPN, 45V, SC-75', 'SOT416' ],
			[ '2SD2654TLW Tranzystor bipolarny NPN 50 V 150 mA 100 MHz HFE 820 SC-75A 2SD2654TLW 3-Pin', 'SOT416' ],

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
			[ '10-MLF® (3x3)', 'MLF10' ],
			[ 'NIS5135MN1TXG Kontroler Hot Swap, zasilanie 3.1V do 18V, DFN-10', 'DFN10' ],
			[ 'TO-263AB (D²PAK)', 'TO-263AB' ],
			[ 'D²PAK (TO-263AB)', 'TO-263AB' ],
			#[ 'NCP103AMX105TCG Stabilizator napięcia LDO, stały, wejście 1.7V do -5.5V, wyjście 1.05Vnom / 150mA, µDFN-4', 'µDFN4' ], # TODO

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

	/**
	 * @param string $desc
	 *
	 * @dataProvider packagesProvider
	 */
	public function testPackageIsDetected($desc) {
		$this->assertNotFalse(PackageInfo::getPackage($desc));
	}

	/**
	 * @return array
	 */
	public function packagesProvider() {
		$packages = <<<DATA
0603/SOD-523F
1005/SOD-323F
100-LFQFP (14x14)
100-LQFP (14x14)
100-PQFP (14x20)
100-QFP (14x20)
100-TQFP (12x12)
100-TQFP (14x14)
100-TQFP (14x20)
100-VQFP (14x14)
108-BGA (10x10)
10-CLCC (7x5)
10-DFN (3x3)
10-LFCSP-WD (3x3)
10-MLF® (3x3)
10-MSOP-EP
10-MSOP-PowerPad
10-MSOP
10-QFN (3x3)
10-uMAX
10-VSON (3x3)
10-VSSOP
10-WSON (3x3)
112-LQFP (20x20)
113-BGA Microstar Junior (7x7)
1152-FBGA (35x35)
1152-FCBGA (35x35)
1156-FCBGA (35x35)
119-PBGA (14x22)
120-LQFP (16x16)
121-TFBGA (10x10)
128-LQFP (14x20)
128-TQFP (14x14)
128-TQFP (14x20)
12-DFN (3x3)
12-DFN (4x3)
12-MSOP-EP
12-MSOP
132-CSPBGA (8x8)
144-FPBGA (13x13)
144-LFQFP (20x20)
144-LQFP (20x20)
144-TQFP (20x20)
14-DFN (4x3)
14-DHVQFN (2.5x3)
14-DIP
14-HTSSOP
14-PDIP
14-SOIC
14-SOP
14-SO
14-SSOP
14-TSSOP
14-VQFN (3.5x3.5)
1517-FBGA (40x40)
1517-FCBGA (40x40)
165-CABGA (13x15)
165-FBGA (13x15)
16-DFN (5x3)
16-DHVQFN (2.5x3.5)
16-DIP
16-HTSSOP
16-LFCSP-VQ (4x4)
16-LFCSP-WQ (3x3)
16-LFCSP-WQ (4x4)
16-MSOP-EP
16-MSOP
16-PDIP
16-QFN-EP (3x3)
16-QFN (3x3)
16-QFN (4x4)
16-QSOP
16-SOIC
16-SOP
16-SO
16-SSOP
16-TQFN (3x3)
16-TQFN (4x4)
16-TQFN (5x5)
16-TSSOP-EP
16-TSSOP
16-VQFN (4x4)
16-WQFN (3x3)
16-WQFN (4x4)
1760-FCBGA (42.5x42.5)
176-LQFP (24x24)
18-PDIP
18-SOIC
1932-FBGA (45x45)
208-CABGA (15x15)
208-PQFP (28x28)
20-HTSSOP
20-LFCSP-WQ (4x4)
20-LSSOP
20-PDIP
20-PLCC (9x9)
20-QFN-EP (4x4)
20-QFN (3x4)
20-QFN (4x4)
20-QSOP
20-SOIC
20-SO
20-SSOP/QSOP
20-SSOP
20-TQFN (4x4)
20-TSSOP-EP
20-TSSOP
20-VQFN (3.5x4.5)
24-HTSSOP
24-LFCSP-WQ (4x4)
24-PDIP
24-QFN (4x4)
24-QSOP
24-SOIC
24-SO
24-SSOP/QSOP
24-SSOP
24-TQFN (4x4)
24-TSSOP-EP
24-TSSOP
24-VQFN (4x4)
24-WQFN (4x4)
256-BGA (17x17)
256-CABGA (17x17)
256-FBGA (17x17)
256-FPBGA (17x17)
256-FTBGA (17x17)
28-HTSSOP
28-PDIP
28-PLCC (11.51x11.51)
28-QFN-S (6x6)
28-QFN (4x5)
28-QFN (5x5)
28-QFN (6x6)
28-QSOP
28-SOIC
28-SPDIP
28-SSOP
28-TQFN (5x5)
28-TSSOP-EP
28-TSSOP
28-UQFN (4x4)
30-TSSOP
324-FBGA (19x19)
32-LFCSP-VQ (5x5)
32-LFCSP-WQ (5x5)
32-LQFP (7x7)
32-PLCC (14x11.46)
32-PLCC
32-QFN (5x5)
32-SOJ
32-TQFN-EP (5x5)
32-TQFP (5x5)
32-TQFP (7x7)
32-VFQFPN (5x5)
32-VQFN (4x4)
32-VQFN (5x5)
32-WQFN (5x5)
36-SSOP
38-QFN (5x7)
38-TSSOP-EP
38-TSSOP
400-VFBGA (17x17)
40-PDIP
40-QFN (6x6)
40-UQFN (5x5)
40-VQFN (6x6)
44-LQFP (10x10)
44-MQFP (10x10)
44-PLCC (16.58x16.58)
44-PLCC (16.59x16.59)
44-QFN (8x8)
44-TQFP (10x10)
44-TSOP2 (10.2x18.4)
44-TSOP II
44-VQFN (7x7)
44-VTLA (6x6)
484-FBGA (23x23)
484-FCBGA (23x23)
484-FPBGA (23x23)
484-UBGA (19x19)
48-HTQFP (7x7)
48-LFCSP-VQ (7x7)
48-LFQFP (7x7)
48-LQFP (7x7)
48-QFN (7x7)
48-SSOP
48-TFBGA (6x8)
48-TQFP (7x7)
48-TSOP I
48-TSOP
48-TSSOP
48-TVSOP
48-VFBGA (6x8)
48-VQFN (7x7)
48-WQFN (7x7)
4-DSBGA
4-SOP
4-USP (1.2x1.6)
4-X2SON (1x1)
4-XDFN (1x1)
52-LQFP (10x10)
52-PLCC (19.13x19.13)
54-TSOP II
56-QFN (8x8)
56-SSOP
56-TSOP
56-TSSOP
5-DDPAK
5-DFN (5x6) (8-SOFL)
5-DSBGA
5-SSOP
5-TSOP
5-TSSOP
5-VSOF
63-VFBGA (9x11)
64-HTQFP (10x10)
64-LFCSP-VQ (9x9)
64-LFQFP (10x10)
64-LQFP (10x10)
64-LQFP (12x12)
64-PQFP (20x14)
64-QFN (9x9)
64-QFP (14x14)
64-QFP (14x20)
64-TQFP (10x10)
64-TQFP (14x14)
64-VQFN (9x9)
672-FBGA (27x27)
676-FBGA (27x27)
676-FCBGA (27x27)
68-PLCC (24.21x24.21)
68-PLCC (24.23x24.23)
68-QFN (8x8)
6-CLCC (7x5)
6-DFN (2x2)
6-DFN (2x3)
6-DFN (3x3)
6-DSBGA
6-HSOP
6-MicroPak
6-MLF® (2x2)
6-SON (1.45x1)
6-SON (2x2)
6-TMLF® (1.6x1.6)
6-TSOP
6-TSSOP
6-USPC (1.8x2)
6-WDFN (2x2)
6-WSON (1.5x1.5)
6-WSON (3x3)
6-XSON, SOT886 (1.45x1)
780-FBGA (29x29)
780-HBGA (33x33)
783-FCPBGA (29x29)
80-LQFP (12x12)
80-LQFP (14x14)
80-QFP (14x14)
80-TQFP (12x12)
80-TQFP (14x14)
84-PLCC (29.21x29.21)
84-PLCC (29.31x29.31)
896-FBGA (31x31)
8-CERDIP
8-DFN-EP (3x3)
8-DFN-S (6x5)
8-DFN (2x2)
8-DFN (2x3)
8-DFN (3x2)
8-DFN (3x3)
8-DFN (5x6)
8-DIP
8-HTSOP-J
8-LFCSP-WD (3x3)
8-MiniSO
8-MLF® (2x2)
8-MSOP-EP
8-MSOP-PowerPad
8-MSOP
8-PDIP
8-SOIC-EP
8-SOIC
8-SOIJ
8-SON (3x3)
8-SON (A) (2.9x2.8)
8-SOP-EP
8-SOP-J
8-SOP
8-SO PowerPad
8-SO
8-TDFN-EP (3x3)
8-TDFN (2x3)
8-TDFN (3x3)
8-TSSOP
8-UDFN (2x3)
8-uMAX
8-VSSOP
8-WDFN (3.3x3.3)
8-WSON (2x2)
8-WSON (4x4)
8-XSON, SOT996-2 (2x3)
900-FCBGA (31x31)
90-TFBGA (8x13)
96-TWBGA (9x13)
ALF2
Axial
D2PAK-3
D²PAK (TO-263AB)
D2PAK
D-5A
D-5B
DDPAK/TO-263-3
DDPAK/TO-263-5
DDPAK/TO-263-7
DFN1006-2
Die
DO-15
DO-200AA, R62
DO-200AB, B-PUK
DO-201AD
DO-203AA
DO-203AB (DO-5)
DO-203AB
DO-204AC (DO-15)
DO-204AL (DO-41)
DO-205AA (DO-8)
DO-205AB, DO-9
DO-213AA
DO-213AB (MELF, LL41)
DO-213AB
DO-214AA (SMBJ)
DO-214AA (SMB)
DO-214AB, (SMC)
DO-214AC (SMAJ)
DO-214AC (SMA)
DO-214AC
DO-216AA
DO-216
DO-219AB (SMF)
DO-220AA (SMP)
DO-35 (DO-204AH)
DO-35
DO-41
DO-4
DO-5
DO-7
DPAK-3
D-PAK (TO-252AA)
D-Pak
DPAK
E-Line (TO-92 compatible)
EMD2
EMT3
GBJ
GBU
HSNT-4-A
HSNT-4-B
HSNT-6A
HSNT-6-B
I2PAK
I-Pak
ISOPLUS247™
ISOTOP®
ITO-220AB
ITO-220AC
LFPAK56, Power-SO8
MicroMELF
Mini3-G3-B
Mini MELF
Module
MPT3
PG-DSO-8
PG-SOT23-3
PG-TDSON-8
PG-TO-220-3
PG-TO247-3
PG-TO252-3
PG-TO263-3-2
PLUS247™-3
PMDS
PMDU
Power56
PowerDI3333-8
PowerDI5060-8
PowerDI™ 123
PowerFlat™ (5x6)
PowerPAK® 1212-8
PowerPAK® SO-8
SC-70-3 (SOT323)
SC-70-3
SC-70-4
SC-70-5
SC-70-6
SC-82AB
SC-88A (SC-70-5 / SOT-353)
SC-88/SC70-6/SOT-363
SM8
SMA (DO-214AC)
SMA
SMBG (DO-215AA)
SMBJ (DO-214AA)
SMB (DO-214AA)
SMB
SMC
SMini2-F5-B
SMini3-F2-B
SMT3
SMV
SNT-4A
SNT-6A (H)
SNT-6A
SNT-8A
SOD-123FL
SOD-123F
SOD-123
SOD-323F
SOD-323
SOD-523
SOD-57
SOD-64
SOD-80C
SOD-80 MiniMELF
SOD-80 QuadroMELF
SOD-80
SOT-143-4
SOT-143
SOT-223-3
SOT-223-4
SOT-223-5
SOT-223-6
SOT-223
SOT-227B
SOT-227
SOT-23-3 (TO-236)
SOT-23-3
SOT-23-5
SOT-23-6
SOT-23-8
SOT-23
SOT-25
SOT-26
SOT-323-3
SOT-323
SOT-353
SOT-363
SOT-523
SOT-563
SOT-666
SOT-89-3
SOT-89-5
SOT-89
SP6
SSMini3-F3-B
SSOT-24
SST3
T-18
Three Tower
TO-220-3
TO-220-5
TO-220AB
TO-220AC
TO-220FP
TO-220F
TO-220
TO-236AB (SOT23)
TO-247-3
TO-247AC
TO-247AD
TO-247 [B]
TO-247
TO-252-3
TO-252-5
TO-252AA
TO-252, (D-Pak)
TO-252
TO-262AA
TO-262
TO-263-5
TO-263AB (D²PAK)
TO-263AB
TO-263 (D2Pak)
TO-263 (D²Pak)
TO-263
TO-268
TO-277A (SMPC)
TO-3P
TO-92-3
TO-92
TSOT-23-5
TSOT-23-6
TSOT-23-8
TSOT-5
Twin Tower
UMD2
UMT3
US6
US8
VMT3
X2-DFN1010-4
DATA;

		return array_map(
			function($item) {
				return [$item];
			},
			explode("\n", trim($packages))
		);
	}

}
