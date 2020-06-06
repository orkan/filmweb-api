D:\Orkan\Localhost\htdocs\filmweb\vendors\vendor\varabi\README.md

	/**
     * Dla filmu - sprawdzić kiedy będzie w telewizji
     */
    List<Broadcast> broadcasts = fa.getBroadcasts(film.getId(), 0, 20);
    for (Broadcast b : broadcasts) {
        
        b.getChannelId();   // ID kanału TV
        b.getDate();        // data emisji
        b.getTime();        // godzina emisji
        b.getDescription(); // krótkie info (gatunek, odcinek i sezon dla serialu)
    }