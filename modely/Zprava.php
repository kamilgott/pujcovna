<?php


// Spravuje kolekci zpráv, které se zobrazí uživateli po dokončení požadavku
// Kolekce je uložena v SESSION, zprávy se tedy zobrazí i po přesměrování

class Zprava
{

	// Přidá zprávu (jako text) do kolekce zpráv
	public static function zobraz($zprava)
	{
		if (isset($_SESSION['zpravy']))
			$_SESSION['zpravy'][] = $zprava;
		else
			$_SESSION['zpravy'] = array($zprava);
    }

	// Vrátí všechny zprávy v kolekci
    public static function vratZpravy()
    {
		if (isset($_SESSION['zpravy']))
		{
			$zpravy = $_SESSION['zpravy'];
			unset($_SESSION['zpravy']);
        	return $zpravy;
		}
		else
			return array();
    }
	
}