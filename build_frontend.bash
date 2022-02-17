#!/bin/bash

build_name=$(date "+%Y%m%d%H%M%S")
public_dir=/var/www/html/cycling/public
build_root=${public_dir}/build


find $build_root -type f -delete

cat \
	"${public_dir}/leaflet.css"\
	"${public_dir}/MarkerCluster.css"\
	"${public_dir}/app.css"\
| yui-compressor --type css -o "${build_root}/${build_name}.css"

gzip -c -1 "${build_root}/${build_name}.css" > "${build_root}/${build_name}.cssgz"


cat "${public_dir}/leaflet.js" > "${build_root}/${build_name}.js"
echo $'\n\n' >> "${build_root}/${build_name}.js"
cat "${public_dir}/leaflet.markercluster.js" >> "${build_root}/${build_name}.js"
echo $'\n\n' >> "${build_root}/${build_name}.js"

cat \
	"${public_dir}/app.js"\
| uglifyjs --compress --mangle >> "${build_root}/${build_name}.js"

gzip -c -1 "${build_root}/${build_name}.js" > "${build_root}/${build_name}.jsgz"




layout_file=resources/views/production_asserts.blade.php
perl -0pe "s/(build\/)(\d{14})(.)(js|css)/\1\L${build_name}\3\4/gms" $layout_file > /tmp/$build_name
cat /tmp/$build_name > $layout_file

