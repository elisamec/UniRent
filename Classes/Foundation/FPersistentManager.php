<?php
class FPersistentManager {
    private static $instance;
    private function __construct()
    {
        
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new FPersistentManager();
        }
        return self::$instance;
    }
    //findAccommodation(DateTime fromDate, string place), chiede query a FAccommodation, @return array
    //load(int idObj), query ad F($class) @return object;
    //store(E($class)), salva l'oggetto nel DB, @return bool;
    //update(E($class)), aggiorna l'oggetto nel db, @return bool;
    //delete(E($class)), cancella l'ogetto nel DB, @return bool;
    //getWaitingReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation in attesa di conferma relative a un proprietario, @return array<Reservation>
    //getAcceptedReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation accettate e in attesa di pagamento relative a un proprietario, @return array<Reservation>
    //

}