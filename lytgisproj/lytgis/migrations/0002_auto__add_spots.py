# -*- coding: utf-8 -*-
from south.utils import datetime_utils as datetime
from south.db import db
from south.v2 import SchemaMigration
from django.db import models


class Migration(SchemaMigration):

    def forwards(self, orm):
        # Adding model 'Spots'
        db.create_table(u'lytgis_spots', (
            (u'id', self.gf('django.db.models.fields.AutoField')(primary_key=True)),
            ('code', self.gf('django.db.models.fields.CharField')(max_length=20)),
            ('poly', self.gf('django.contrib.gis.db.models.fields.PointField')()),
        ))
        db.send_create_signal(u'lytgis', ['Spots'])


    def backwards(self, orm):
        # Deleting model 'Spots'
        db.delete_table(u'lytgis_spots')


    models = {
        u'lytgis.spots': {
            'Meta': {'object_name': 'Spots'},
            'code': ('django.db.models.fields.CharField', [], {'max_length': '20'}),
            u'id': ('django.db.models.fields.AutoField', [], {'primary_key': 'True'}),
            'poly': ('django.contrib.gis.db.models.fields.PointField', [], {})
        }
    }

    complete_apps = ['lytgis']