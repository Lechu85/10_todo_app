<?php
namespace App\Config;

enum TaskStatus:int implements TaskStatusInterface

{
	case Nowe = 1;
	case Odczytane = 2;
	case Oczekiwanie_na_odpowiedz = 3;
	case Oczekiwanie_na_dostawe = 4;
	case W_przygotowaniu = 5;
	case Finalizacja = 6;
	case Zakonczone = 7;
	case Zakonczone_czesciowo = 8;
	case Anulowane = 9;
	case Wznowione = 10;
	case Odlozone = 11;

	public function getName():string
	{

		return match ($this) {
			self::Nowe => 'Nowe',
			self::Odczytane => 'Odczytane',
			self::Oczekiwanie_na_odpowiedz => 'Oczekiwanie na odpowiedź',
			self::Oczekiwanie_na_dostawe => 'Oczekiwanie na dostawę',
			self::W_przygotowaniu => 'W przygotowaniu',
			self::Finalizacja => 'Finalizacja',
			self::Zakonczone => 'Zakonczone',
			self::Zakonczone_czesciowo => 'Zakończone częściowo',
			self::Anulowane => 'Anulowane',
			self::Wznowione => 'Wznowione',
			self::Odlozone => 'Odlozone',

		};
	}

}
