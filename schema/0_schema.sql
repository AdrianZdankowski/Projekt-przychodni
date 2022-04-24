CREATE TABLE `klienci` (
  `id_klienta` int(11) NOT NULL,
  `login` varchar(24) NOT NULL UNIQUE,
  `haslo` char(60) NOT NULL,
  `imie` varchar(32) NOT NULL,
  `nazwisko` varchar(48) NOT NULL,
  `email` varchar(40) NOT NULL UNIQUE,
  `nr_tel` bigint(9) NOT NULL UNIQUE,
   PRIMARY KEY (`id_klienta`)
);

CREATE TABLE `specjalizacje` (
  `id_specjalizacji` int(11) NOT NULL,
  `nazwa` varchar(40) NOT NULL,
   PRIMARY KEY (`id_specjalizacji`)
);

CREATE TABLE `lekarze` (
  `id_lekarza` int(11) NOT NULL,
  `id_specjalizacji` int(11) NOT NULL,
  `imie` varchar(32) NOT NULL,
  `nazwisko` varchar(48) NOT NULL,
  `email` varchar(40) NOT NULL UNIQUE,
  `nr_tel` bigint(9) NOT NULL UNIQUE,
  PRIMARY KEY (`id_lekarza`),
  FOREIGN KEY (`id_specjalizacji`) REFERENCES specjalizacje(id_specjalizacji)
);

CREATE TABLE `wizyta` (
  `id_wizyty` int(11) NOT NULL PRIMARY KEY,
  `id_klienta` int(11) NOT NULL,
  `id_lekarza` int(11) NOT NULL,
  `data_wizyty` date NOT NULL,
  `godzina_wizyty` time NOT NULL,
  `dolegliwosc` varchar(40) NOT NULL,
  `opis` varchar(125) NOT NULL
);

ALTER TABLE wizyta 
ADD FOREIGN KEY (id_klienta) 
REFERENCES klienci(id_klienta);

ALTER TABLE wizyta
ADD FOREIGN KEY (id_lekarza) 
REFERENCES lekarze(id_lekarza);

COMMIT;