Testing Carrier Magento Modul (Socket)
Rev. 01 V0.1.0 unreleased
/**
 * @category   Webvisum
 * @package    Testing_Carrier
 * @author     Andreas Vogt <support@webvisum.de>
 */
WICHTIG: Voraussetzung für diese Modul ist ein GLS UNI BOX Account.
1.	Magento
Melden Sie sich im Magento Onlineshop Backend an: <Shop_URL>/admin. Voraussetzungen sind lediglich ein Administrationsaccount oder ein zugriffsbeschränkter Account mit Gruppenzugangsberechtigungen zu „Shipments“ und „Gls“. Entsprechende Berechtigungen können gesetzt werden unter: System > Permission > Roles . 
2.	Einstellungen und Konfiguration
2.1.	Konfiguration
Gls spezifische Einstellungen finden Sie unter System -> Konfiguration.
Dort sehen Sie in der linken Spalte: „Gls Unibox“.
 
Account Einstellungen: Diese Einstellungen müssen mit den von Ihrem Depot zugeteilten Daten gefüllt werden. Falsche oder fehlerhafte Daten haben ein nicht funktionierendes System zur Folge.
Versender Daten: Jede Sendung die Sie anstoßen wird mit diesen Versenderdaten getätigt. Gesonderte Mandanten und Abholstationen können Sie im Punkt 2.2 angeben.
Versandlabels: Einstellungen zu den Gls Versandlabel Pdf’s werden hier getätigt. Beachten Sie, dass Sie entweder A4 oder A5 Papiergröße einstellen können, jedoch beim Druck immer „in Originalgröße (unverändert)“ in Ihrem entsprechenden Pdf Betrachter auswählen müssen. Die Größe des Labels auf dem Pdf ist fix, die Position dessen können Sie in diesem Punkt einstellen. Numerische Angabe der X- & Y-Position wird in Pdf-typischen Points gemacht (Umrechnung : 1 mm entspricht 2,8 Points). Sollten Sie den Punkt „Sendungsverfolgung automatisch anlegen“ aktivieren, so wird die Gls Paketnummer automatisch zu den Trackinginformationen der Magento Sendung hinzugefügt. Aktivieren Sie „erstellte Pdfs speichern“, so werden alle Labels welche Sie per „druck“ generieren zusätzlich unzugänglich für dritte in dem von Ihnen unterhalb angegebenen Ordner gespeichert.
2.2.	Mandanten
 
Mandanten können Sie anlegen unter Gls -> Mandanten Pflegen.
Klicken Sie dort auf den Button „Neue Mandanten hinzufügen“ in der oberen rechten Ecke.
Unvollständige oder falsche Daten führen zu Fehlermeldungen der Unibox und es wird weder Label gespeichert noch gedruckt. Zum späteren wiedererkennen des Mandanten wählen Sie einen „Versendernamen“ aus, anhand dessen Sie in der Sendungsaufgabe den Mandanten wiedererkennen können. Der Punkt „Notizen“ ist lediglich für Ihre internen Notizen zu diesem Mandanten und späteren Einordnung (durch Dritte mit Zugang) gedacht.
3.	Sendungen
3.1.	Sendung aufgeben
Sie können Gls Sendungen nur aufgeben wenn zu einer Bestellung (Order) ein Shipment erstellt wurde. Wählen Sie hierzu den Menüpunkt Sales -> Order und erstellen Sie ein Shipment mit dem Button „Shipment“, welcher sich in der oberen rechten Ecke befindet.
Sobald ein Shipment angelegt wurde wählen Sie dieses im Menüpunkt Sales -> Shipment aus. Ihnen bietet sich nun ein Dialogfeld unterhalb der Tackingnummer-Verwaltung.
 
Wählen Sie Ihren Service und den Mandanten (Versenden von:) aus. Tragen Sie zudem das Gewicht Ihrer Sendung in das dafür vorgesehene Feld ein. Sollten Sie ein nicht zulässiges Gewicht für einen Versandservice angeben wird die Unibox eine Fehlermeldung zurückgeben und es erfolgt keine Speicherung sowie kein Druck des Labels. Eine Prüfung auf zulässiges Versandgewicht und Wahrhaftigkeit des eingegebenen Gewichtes durch das Modul findet nicht statt.
Solle für das Shipment, bestimmt durch das Empfängerland, Expressversand möglich sein, so wählen Sie den Service „Express“ und füllen Sie die zusätzlichen Felder zum Expressversand unterhalb aus. Sollten Sie „Business Parcel“ ausgewählt haben bleiben gemachte Einstellungen in diesem Bereich ohne Auswirkung. Sollte die Zustellung Ihres gewählten Express Services nicht in diesem Zeitrahmen möglich sein, oder fälschlicherweise die Samstags-Expresszustellung gewählt worden sein, wird die Unibox eine Fehlermeldung zurückgeben und die Speicherung und Ausdruck findet nicht statt.
Das Feld Notiz ist lediglich für die Tabellenbeschriftung und Speicherung des Eintrags in der Datenbank, sowie als Information für den Kunden falls es sich um eine Teillieferung handelt. Wählen Sie eine aussagekräftige Notiz um die Sendung später Ihrem Paket zuzuordnen zu können.
Die Zeile „Paketnummer(Tracking)“ können Sie nutzen um oberhalb für Ihren Kunden die Trackinginformation einzutragen, damit dieser eine Sendungsverfolgung zu dem Paket in seinem Benutzeraccount einsehen kann.
3.2.	Frankatur 
Sollte das Senderland Frankatur des Paketes benötigen wird dieses zusätzlich eingeblendet.  
Ein Beispiel hierfür ist die Schweiz.
3.3.	Euro (Express) Parcel
Auslandssendungen können von dem System einwandfrei gehandhabt werden. National Delivery Information wird im Label korrekt behandelt. Expresssendungen außerhalb Deutschlands werden bei der Labelgenerierung ebenfalls berücksichtigt (T105 statt T100).
3.4.	Sendung stornieren
Sollten Sie eine Sendung stornieren wollen, so drücken Sie auf den Link stornieren in der Tabelle der Gls Sendungen zu einem Magento Shipment. Eine aussagekräftige Notiz zu der Gls Sendung im Vorfeld, kann die Identifikation der zu stornierenden Sendung in diesem Punkt erheblich vereinfachen. Sollte die Unibox die Stornierung genehmigen, so ist die Vergabe dieser Gls Paketnummer sowie der Ausdruck nicht mehr möglich.
4.	Sendungsverlauf einsehen
Eine komplette Liste der Gls Sendungen können Sie unter Gls -> Sendungen verwalten einsehen. 
 
Sie können gespeicherte Sendungen nicht löschen. Alternativ hierzu navigieren Sie bitte wie in Punkt 3.4 beschrieben zu der Magento Sendung um die entsprechende Gls Sendung zu stornieren.
Beachten Sie zusätzlich, dass Sie diese Liste mindestens 1 Jahr vorhalten müssen. Diese Datensätze steuern zudem den Paketnummernkreis. Sollten Sie manuelle Eingriffe in die Datenbank vornehmen kann dies zu unerwartetem Verhalten beim Anlegen von Gls Sendungen führen.
