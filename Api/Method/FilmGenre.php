<?php

namespace Orkan\Filmweb\Api\Method;

class FilmGenre
{
	/**
	 * Genre types
	 * @formatter:off
	 */
	const ANIMATION          =  2;  // animacja
	const BIOGRAPHICAL       =  3;  // biograficzny
	const FOR_CHILDREN       =  4;  // dla dzieci
	const DOCUMENTARY        =  5;  // dokumentalny
	const DRAMA              =  6;  // dramat
	const EROTIC             =  7;  // erotyczny
	const FAMILY             =  8;  // familijny
	const FANTASY            =  9;  // fantasy
	const SURREAL            = 10;  // surrealistyczny
	const HISTORICAL         = 11;  // historyczny
	const HORROR             = 12;  // horror
	const COMEDY             = 13;  // komedia
	const COSTUME            = 14;  // kostiumowy
	const DETECTIVE_STORY    = 15;  // kryminał
	const MELODRAMA          = 16;  // melodramat
	const MUSICAL            = 17;  // musical
	const MOVIE_STORIES      = 18;  // nowele filmowe
	const DRAMA2             = 19;  // obyczajowy
	const ADVENTURE          = 20;  // przygodowy
	const SENSATIONAL        = 22;  // sensacyjny
	const THRILLER           = 24;  // thriller
	const WESTERN            = 25;  // western
	const WAR                = 26;  // wojenny
	const FILM_NOIR          = 27;  // film-noir
	const ACTION             = 28;  // akcja
	const COMEDY_CUSTOM      = 29;  // komedia obycz.
	const ROMANCE_COMEDY     = 30;  // komedia rom.
	const ROMANCE            = 32;  // romans
	const SCIENCE_FICTION    = 33;  // sci-fi
	const DRAMA3             = 37;  // dramat obyczajowy
	const PSYCHOLOGICAL      = 38;  // psychologiczny
	const SATIRE             = 39;  // satyra
	const CATASTROPHIC       = 40;  // katastroficzny
	const FOR_YOUNG_PEOPLE   = 41;  // dla młodzieży
	const FAIRY_TALE         = 42;  // baśń
	const POLITICAL          = 43;  // polityczny
	const MUSIC              = 44;  // muzyczny
	const STUDY              = 45;  // etiuda
	const THRILLER2          = 46;  // dreszczowiec
	const BLACK_COMEDY       = 47;  // czarna komedia
	const SHORT              = 50;  // krótkometrażowy
	const RELIGIOUS          = 51;  // religijny
	const LEGAL              = 52;  // prawniczy
	const GANGSTER           = 53;  // gangsterski
	const KARATE             = 54;  // karate
	const BIBLICAL           = 55;  // biblijny
	const DOCUMENTED         = 57;  // dokumentalizowany
	const CRIMINAL_COMEDY    = 58;  // komedia kryminalna
	const HISTORICAL_DRAMA   = 59;  // dramat historyczny
	const FILM_GROTESQUE     = 60;  // groteska filmowa
	const SPORTS             = 61;  // sportowy
	const POETIC             = 62;  // poetycki
	const SPY                = 63;  // szpiegowski
	const EDUCATIONAL        = 64;  // edukacyjny
	const COURT_DRAMA        = 65;  // dramat sądowy
	const ANIME              = 66;  // anime
	const DUMB               = 67;  // niemy
	const MANTLE_AND_SWORD   = 68;  // płaszcza i szpady
	const SOCIAL_DRAMA       = 69;  // dramat społeczny
	const FICTIONALIZED_DOC  = 70;  // fabularyzowany dok.
	const XXX                = 71;  // xxx
	const MARTIAL_ARTS       = 72;  // sztuki walki
	const NATURAL            = 73;  // przyrodniczy
	const DOCUMENTARY_COMEDY = 74;  // komedia dokumentalna
	const LITERARY_FICTION   = 75;  // fikcja literacka
	const PROPAGANDA         = 76;  // propagandowy
	/* @formatter:on */
}
