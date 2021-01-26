



import urllib.request
import json
import pymysql
from datetime import date, datetime, timedelta



BaseURL = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/weatherdata/'
ApiKey='YX2GB1F31P3PK9L3RGI6FQKUI'
#us or metric
UnitGroup='metric'
Locations='Vilnius'
#FORECAST or HISTORY
QueryType='FORECAST'
#1=hourly, 24=daily
AggregateHours='1'
#Params for history only
StartDate = ''
EndDate=''

# Set up the specific parameters based on the type of query
if QueryType == 'FORECAST':
    print(' - Fetching forecast data')
    QueryParams = 'forecast?aggregateHours=' + AggregateHours + '&unitGroup=' + UnitGroup + '&shortColumnNames=true'
else:
    print(' - Fetching history for date: ', DateParam)

    # History requests require a date.  We use the same date for start and end since we only want to query a single date in this example
    QueryParams = 'history?aggregateHours=' + AggregateHours + '&unitGroup=' + UnitGroup +'&startDateTime=' + StartDate + 'T00%3A00%3A00&endDateTime=' + EndDate + 'T00%3A00%3A00'

Locations='&locations='+Locations

ApiKey='&key='+ApiKey

# Build the entire query
URL = BaseURL + QueryParams + Locations + ApiKey+"&contentType=json"

print(' - Running query URL: ', URL)
print()


response = urllib.request.urlopen(URL)
data = response.read()
weatherData = json.loads(data.decode('utf-8'))

errorCode=weatherData["errorCode"] if 'errorCode' in weatherData else 0

if (errorCode>0):
    print("An error occurred retrieving the data:"+weatherData["message"])
    exit("Script terminated")

print( "Connecting to mysql database")
cnx = pymysql.connect(host='localhost',
    user='weather_data',
    passwd='peletrunas',
    database='weather_database')

cursor = cnx.cursor()

# In this simple example, clear out the existing data in the table

delete_weather_data=("TRUNCATE TABLE `weather_database`.`weather_data`")
cursor.execute(delete_weather_data)
cnx.commit()

# Create an insert statement for inserting rows of data
insert_weather_data = ("INSERT INTO `weather_database`.`weather_data`"
                "(`name`,`latitude`,`longitude`,`datetime`,`temp`, `windchill`, `precip`,`wspd`,`wdir`,`wgust`,`pressure`, `humidity`, conditions)"
                "VALUES (%(name)s, %(latitude)s, %(longitude)s, %(datetime)s, %(temp)s, %(windchill)s, %(precip)s, %(wspd)s, %(wdir)s, %(wgust)s, %(pressure)s, %(humidity)s, %(conditions)s)")

# Iterate through the locations
locations=weatherData["locations"]
for locationid in locations:
    location=locations[locationid]
    # Iterate through the values (values are the time periods in the weather data)
    for value in location["values"]:
        data_wx = {
        'name': location["name"],
        'latitude': location["latitude"],
        'longitude': location["longitude"],
        'datetime': datetime.utcfromtimestamp(value["datetime"]/1000.),
        'temp': value["temp"],
        'windchill': value["windchill"],
        'precip': value["precip"],
        'wspd': value["wspd"],
        'wdir': value["wdir"],
        'wgust': value["wgust"],
        'pressure': value["sealevelpressure"],
        'humidity': value["humidity"],
        'conditions': value["conditions"]
        }
        cursor.execute(insert_weather_data, data_wx)
        cnx.commit()

cursor.close()
cnx.close()
print( "Database connection closed")

print( "Done")
