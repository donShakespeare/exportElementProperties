https://github.com/donShakespeare/exportElementProperties

Reasons for this plugin
  1. Native MODX procedure fails to export any properties larger than a hobbit mitten
      Request-URI Too Long
      The requested URL's length exceeds the capacity limit for this server = FAIL FAIL
  2. xButtons, which is beautiful, tries to solve this issue. but it throws tantrums for me every now and now = FAIL FAIL

************
Install. Done!

Disable need for &p param in plugin's properties
Now add url parameter &p to any element Manager Page URL you are on.

  example.com/manager/?a=element/plugin/update&id=21&p
  example.com/manager/?a=element/template/update&id=1&p
  example.com/manager/?a=element/snippet/update&id=118&p
  example.com/manager/?a=element/chunk/update&id=12&p
  example.com/manager/?a=element/tv/update&id=22&p

For PropertySets (go to pset page first, and add value {name of pset} to &p)
  example.com/manager/?a=element/propertyset&p=myPSetName

To get desired order of JSON objects, intentionally sort your props and save