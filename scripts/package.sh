#!/bin/bash

onion -d compile \
      --executable \
      --bootstrap scripts/mcucli.php \
      --lib . \
      --exclude bin \
      --exclude scripts \
      --exclude .git \
      --exclude .DS_Store \
      --exclude mcucli \
      --output mcucli.phar
mv mcucli.phar mcucli
chmod +x mcucli
