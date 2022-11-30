<?php
namespace App\Config;


enum TaskStatus: int
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
}
