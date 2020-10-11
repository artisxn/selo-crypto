#!/bin/sh

set -e
exec /var/lib/nikto/replay.pl "$@"
