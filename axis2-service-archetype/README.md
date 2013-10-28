Maven Archetype to Create a Simple Axis2 Service Project
========================================================

First git clone this project, cd to axis2-service-archetype directory and do:
```
$ mvn install
```

Then form a different directory (where the new axis2 service project need to be) do:
```
$ mvn archetype:generate -DarchetypeGroupId=org.ruchith.tools -DarchetypeArtifactId=axis2-service-archetype -DarchetypeVersion=1.0-SNAPSHOT -DgroupId=org.test -DartifactId=service-foo -DinteractiveMode=false
```

Replace: -DgroupId=org.test -DartifactId=service-foo with appropriate values.
