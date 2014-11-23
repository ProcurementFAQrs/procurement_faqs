from django.db import models
from django.contrib.gis.db import models

class Spots(models.Model):
    code = models.CharField(max_length=20)
    poly = models.PointField()
    objects = models.GeoManager()

class Point(models.Model):
	osm_id = models.IntegerField(primary_key=True)
	name = models.TextField(null=True, db_index=True)
	railway = models.TextField(null=True, db_index=True)
	place = models.TextField(null=True, db_index=True)
	way  = models.PointField(null=True, srid=900913)

	class Meta:
		db_table = "planet_osm_point"
		managed = False

	objects = models.GeoManager()

class Polygon(models.Model):
	osm_id = models.IntegerField(primary_key=True)
	name = models.TextField(null=True, db_index=True)
	admin_level = models.TextField(null=True, db_index=True)
	boundary = models.TextField(null=True, db_index=True)
	way  = models.PolygonField(null=True, srid=900913)

	class Meta:
		db_table = "planet_osm_polygon"
		managed = False

	objects = models.GeoManager()
