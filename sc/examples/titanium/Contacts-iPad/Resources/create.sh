#!/bin/ksh
ln -s ../../app.js .
ln -s ../../*.html .
for i in $(echo ISC_Containers.js ISC_Core.js ISC_DataBinding.js ISC_Foundation.js ISC_Forms.js ISC_Grids.js); do
  ln -s ../../../../../../../isomorphic/smartclient/client/development/$i .
done
ln -s ../../../../../../../isomorphic/smartclient/skins .

