import os
import sys
import site

# Add the site-packages of the chosen virtualenv to work with
site.addsitedir('/home/berserk/.envs/lytgisproj/local/lib/python2.7/site-packages/')

# Add the app's directory to the PYTHONPATH
sys.path.append('/home/berserk/projects/lytgisproj/')
sys.path.append('/home/berserk/projects/lytgisproj/lytgisproj/')

os.environ['DJANGO_SETTINGS_MODULE'] = 'lytgisproj.settings'

# Activate your virtual env
activate_env=os.path.expanduser("/home/berserk/.envs/lytgisproj/bin/activate_this.py")
execfile(activate_env, dict(__file__=activate_env))

import django.core.handlers.wsgi
application = django.core.handlers.wsgi.WSGIHandler()