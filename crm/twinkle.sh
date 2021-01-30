#!/bin/bash

 echo " Intalling TWINKE call handle tel:// ... "
 [ ! -f /usr/bin/twinkle ] && read -p  '  WARNING: Twinkle not installed in default location!!![ Enter ]'

 echo -ne " Installing app... "
 sleep 1
 cd ~/.local/share/applications/
 wget -q https://mpbx.a4business.com/crm/twinkle.desktop -O twinkle.tmp
 mv twinkle.tmp twinkle.desktop

 xdg-desktop-menu install twinkle.desktop --novendor
 xdg-mime default twinkle.desktop x-scheme-handler/tel 
 xdg-mime default twinkle.desktop x-scheme-handler/callto

 echo '   DONE!  Please test now' &&  exit 0

 echo "Updating system Desktop database does require sudo password:"
 sudo update-desktop-database

 echo " DONE"
 echo " Tips to disable popups"
 echo "=========================="
 echo " Info1:  chrome://flags/#click-to-call-ui "
 echo "      : Disabling chrome://flags/#click-to-call-ui and creating the "[HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Google\Chrome]
"ExternalProtocolDialogShowAlwaysOpenCheckbox"=dword:00000001 registry"  "
