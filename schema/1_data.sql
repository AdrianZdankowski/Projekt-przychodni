INSERT INTO `klienci` (`id_klienta`, `login`, `haslo`, `imie`, `nazwisko`, `email`, `nr_tel`) VALUES
(1, 'cmcasparan0', '$2y$10$WrTVq3PKvpCvhbFNySv2ye5PjjWViv9E384OZwaFQeCplH8FhPVje', 'Cortney', 'Mcasparan', 'cmcasparan0@amazonaws.com', 178201134),
(2, 'sbrunstan1', '$2y$10$GjszFilAtfjyW76g1CkVMuyU.OMfl6k4WLCTGEXQX5zvuSLKZSGvq', 'Storm', 'Brunstan', 'sbrunstan1@cisco.com', 507125969),
(3, 'tepton2', '$2y$10$YZ2iOafEPZdpIwlqtElpf.x0lpB/e0yDS/Q4yJRLW1kO5AiJhnhla', 'Tamarah', 'Epton', 'tepton2@php.net', 805861813),
(4, 'cwerrett3', '$2y$10$j3afvolD0IdACJG9TSUjOOFXxDfX8CNgNpzoM4KFmdc8npTndG9xK', 'Ceil', 'Werrett', 'cwerrett3@free.fr', 582529149),
(5, 'npetyt4', '$2y$10$dwDqsORoxmtYjA9xW.zd0ObfktA/qRTaBsai.rKZm/IFKAIqVyWKO', 'Neila', 'Petyt', 'npetyt4@unesco.org', 784601118),
(6, 'JJanusz', '$2y$10$mVTLtE2ZeYzIX4WkEWDaSeenj.iBKCYY7uRshh2CwDIczSRPN2eES', 'Janusz', 'Jackowski', 'jacek@wp.pl', 758069420),
(8, 'AdamZ', '$2y$10$k4kyadKQ7TMTtfCF1AAgkun4RoXM5DHkcCFpZg6wK1HVBypQMGwKG', 'Adam', 'Zelent', 'adamzelencik@wp.pl', 997532478),
(9, 'KamilW', '$2y$10$uGkS3QLKE/LxOtn1J8m1uOHuTVkTxBldaFKctUea3JIA3/jRfmYrK', 'Kamil', 'Włodarczyk', 'kamilw@gmail.com', 667452321),
(10, 'PHPaweł', '$2y$10$aJP0MysMCVZ5syK6uRaES.QkAZ9aCx6b0jE13I6V51xbzLaa5c8by', 'Paweł', 'Gnob', 'phpawel@kox.com', 696969696),
(11, 'Wojteq', '$2y$10$FsMF5Q.RHfx.cUzEIzh6aOms95oa5j5cJDFNyxV5xQKh7sfzwldrC', 'Wojciech', 'Kula', 'kuladajefula@wp.pl', 857093471),
(12, 'Balcio', '$2y$10$1eqkxkzimuAztRPfN5N2IuXqKg3C66Fi.7dystO5dzFOM/KbjeBnC', 'Bartosz', 'Noga', 'bnoga@wp.pl', 537183514),
(13, 'testernalesnikow', '$2y$10$jlUiBWeaMP1u5z5tSH0ET.YJWSeIb5Bwk/PWnfOenzBpw7WtCi2cK', 'Witold', 'Schab', 'lubieplacki@wp.pl', 142957937),
(14, 'Blum','$2y$10$9LoNWduU1akHp/HZDRwzOu691xgWWQCFCXEIrYTuntsNKTVTWFLjS', 'Krzysztof', 'Blum', 'kblum@wp.pl',987640700),
(15, 'Arcio','$2y$10$Pu/CLVGPNCCAnH8xfDqev.V3VvjwXlVYfDrk3wHgtQe31Q.TFEGA2', 'Arek', 'Ponk', 'ponciu@wp.pl',400283617);

/*Większość haseł to 1234*/


INSERT INTO `specjalizacje` (`id_specjalizacji`, `nazwa`) VALUES
(1, 'Alergolog'),
(2, 'Dermatolog'),
(3, 'Diabetolog'),
(4, 'Endokrynolog'),
(5, 'Epidemiolog'),
(6, 'Kardiolog'),
(7, 'Laryngolog'),
(8, 'Neurolog'),
(9, 'Okulista'),
(10, 'Pediatra'),
(11, 'Psycholog');

INSERT INTO `lekarze` (`id_lekarza`, `id_specjalizacji`, `imie`, `nazwisko`, `email`, `nr_tel`) VALUES
(1, 1, 'Anna', 'Kowalska', 'akowalska@pnpk.pl', 111000111),
(2, 2, 'Andrzej', 'Łoś', 'alos@pnpk.pl', 111222111),
(3, 3, 'Tomasz', 'Kowal', 'tkowal@pnpk.pl', 111333111),
(4, 4, 'Justyna', 'Guma', 'jguma@pnpk.pl', 111444111),
(5, 6, 'Władysław', 'Kret', 'wkret@pnpk.pl', 111555111),
(6, 7, 'Weronika', 'Jakubiak', 'wjakubiak@pnpk.pl', 111666111),
(7, 8, 'Konrad', 'Cichy', 'kcichy@pnpk.pl', 111777111),
(8, 9, 'Maria', 'Wesoła', 'mwesola@pnpk.pl', 111888111),
(9, 10, 'Jakub', 'Wirek', 'jwirek@pnpk.pl', 111999111),
(10, 5, 'Dorota', 'Korona', 'dkorona@pnpk.pl', 222111111),
(11, 11, 'Marta', 'Dudek', 'mdudek@pnpk.pl', 222222111),
(12, 5, 'Konrad','Wirus','kwirus@pnpk.pl', 222222222),
(13, 1, 'Andżela', 'Gajowiec', 'agajowiec@pnpk.pl', 222222333),
(14, 6, 'Marcin', 'Kowalczyk', 'mkowalczyk@pnpk.pl', 222222444),
(15, 10, 'Katarzyna', 'Wojnicz', 'kwojnicz@pnpk.pl', 222222555);

INSERT INTO `wizyta` (`id_wizyty`, `id_klienta`, `id_lekarza`, `data_wizyty`, `godzina_wizyty`, `dolegliwosc`, `opis`) VALUES
(1, 6, 1, '2021-03-26', '16:00:00', 'Katar', ''),
(2, 6, 8, '2021-03-30', '12:30:00', 'Ból oka', ''),
(3, 8, 5, '2021-03-26', '16:00:00', 'Ból serca', ''),
(5, 8, 10, '2021-03-28', '06:30:00', 'Koronawirus', ''),
(6, 6, 11, '2021-03-28', '15:30:00', 'Depresja', 'Śmierć generalnie'),
(7, 8, 6, '2021-04-25', '11:30:00', 'Ból zatok', ''),
(8, 9, 11, '2021-04-28', '15:00:00', 'Porada', '' ),
(9, 11, 8, '2021-04-12', '08:30:00', 'Jaskra', '' ),
(10, 11, 4, '2021-04-18', '10:00:00', 'Tarczyca', '');
