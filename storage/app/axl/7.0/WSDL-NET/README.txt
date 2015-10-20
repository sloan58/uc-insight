Copyright © 2006-2007 Cisco Systems, Inc.

WSDL-NET README File

This readme file contains information specific to the use of the AXLAPI.wsdl in .NET Environment.

Purpose:

To fix the AXL-SOAP Configurability API’s WSDL and XSD files with the explicit purpose for integration with a .NET client. 
The schema file AXLSoap.xsd is only for code generation and has been modified to enusure that future changes in the Database schema will cause minimum breakage to the code generated using WSDL and the schema files in the parent directory must be used for reference and for validation of response.
Some of the changes made are to overcome some issue with .NET's ability to handle complex schemas.

Steps:

1. Run WSDL.exe, make minor changes to generated code

	"wsdl.exe AXLAPI.wsdl axlsoap.xsd" which will result in AXLAPIService.cs.

	Resulting class AXLAPIService in AXLAPIService.cs needs at least three changes:

	a. Create an ICertificatePolicy-derived class which will later be associated with our service. This class is a brute-force approach to policy and certificate management. This is necessary in 5.x and 6.x AXL due to usage of HTTPS.

	public class BruteForcePolicy : System.Net.ICertificatePolicy
	{
		public bool CheckValidationResult(System.Net.ServicePoint sp, System.Security.Cryptography.X509Certificates.X509Certificate cert,
				System.Net.WebRequest request, int problem)
		{        
		return true;
		}
	}

	b. Modify service constructor to take username/password credentials, CallManager IP as an argument, and associate the BruteForcePolicy class with the static CertificatePolicy manager.

	public AXLAPIService(string ccmIp, string user, string password) 
	{
		System.Net.ServicePointManager.CertificatePolicy = new BruteForcePolicy();

		this.Url = "https://" + ccmIp + ":8443/axl/";
		this.Credentials = new System.Net.NetworkCredential(user, password);
	}

	c. .NET uses the expects header differently (http://issues.apache.org/bugzilla/show_bug.cgi?id=31567). There are some workarounds to this problem as listed below:

		i. Override the GetWebRequest method to use HTTP 1.0 due to error between TOMCAT/AXIS and .NET HTTP 1.1 Web Service request mechanism.  

		protected override System.Net.WebRequest GetWebRequest(Uri uri)
		{
			System.Net.HttpWebRequest request = base.GetWebRequest (uri) as System.Net.HttpWebRequest;
			request.ProtocolVersion = System.Net.HttpVersion.Version10; 

			return request;
		}

		ii. Override the GetWebRequest method to manually embed authentication string.  If you do this, do not use the line
		  this.Credentials = new System.Net.NetworkCredential(user, password); 
		  from the constructor provided in point b of this section.

		protected override System.Net.WebRequest GetWebRequest(Uri uri) 
		{ 
			System.Net.HttpWebRequest request =(System.Net.HttpWebRequest)base.GetWebRequest(uri); 
			if (this.PreAuthenticate) 
			{ 
				System.Net.NetworkCredential nc = this.Credentials.GetCredential(uri,"Basic"); 
				if (nc != null) 
				{ 
					byte[] credBuf = new System.Text.UTF8Encoding().GetBytes(nc.UserName + ":" + nc.Password); 
					request.Headers["Authorization"] = "Basic " + Convert.ToBase64String(credBuf); 
				}
			}
			return request; 
		}

		iii. If using wsdl2wse (WSE library) instead of wsdl.exe, you can not override the HTTP version or supply HTTP headers manually.   If one wants to use WSE, you have to set Keep-Alive header to false for the generated class, or set the user-agent to restricted.  This technique will work in lieu of approach in point i and ii above.

 
2. Resolving Errors which Manifest During JIT 

	With the current code, one can compile and attempt to instantiate the AXLAPIService class.  However, one error will manifest as the types are inspected and loaded.

	- Class XUserUserGroup has a field which was generated incorrectly.  One must remove one of the ‘[]’ from the two ‘[][]’ brackets after XUserUserGroupUserRolesUserRole field:  
        
	  public XUserUserGroupUserRolesUserRole[] userRoles;

3. Miscellaneous Issues

	When one adds the definition for new Cisco IP Phone devices to the CallManager database, the original WSDL sent out for that CallManager becomes outdated.  For instance, the XModel enumeration in the Cisco CallManager 4.1.3 does not contain the 7961G-GE model.  
	However, if you install the latest device pack with that device’s information into your 4.1.3 installation, that value will be returned if one were to do a listAllDevices or getPhone command for that phone’s device name, which .NET will throw an exception when it encounters the new model not contained in the definition.
	In other words, almost all the enumerations in AXLEnums.xsd could change in some furture release which might make the developer code to break in some release. So to tackle this, we have changed the type of all the tags that uses any of these enumerations to String and added an annotation to that tag saying where to look for the right value (AXLEnums.xsd). 

 
4. Known issue:        
     I.	Suppose the client stub is generated using wsdl.exe. We may find that some of the fields which has some default values defined in the schema would not work if passed in the AXL request. For example:
        In the updatePhoneReq class of the generated client stub, there is a field named "ignorePresentationIndicators" which has a default value of "False" defined in the schema.

        [System.Xml.Serialization.XmlTypeAttribute(Namespace="http://www.cisco.com/AXL/7.0")]
    	public class UpdatePhoneReq : APIRequest {
        .
        .        .

        [System.Xml.Serialization.XmlElementAttribute(Form=System.Xml.Schema.XmlSchemaForm.Unqualified)]
        [System.ComponentModel.DefaultValueAttribute(false)]
        public bool ignorePresentationIndicators = false;
        .
        .        
	}

        When this tag is sent with a value of false, XmlSerializer will not serialize this tag beacuse of a design restriction in Microsoft.NET Framework 1.0. Please refer to http://support.microsoft.com/kb/325691.       
	To work around this problem, comment out all instances of [System.ComponentModel.DefaultValueAttribute(XXX)] in the generated client stub as also shown below.
        [System.Xml.Serialization.XmlTypeAttribute(Namespace="http://www.cisco.com/AXL/7.0")]
            public class UpdatePhoneReq : APIRequest {
                .
                .                .
                [System.Xml.Serialization.XmlElementAttribute(Form=System.Xml.Schema.XmlSchemaForm.Unqualified)]                
		// Comment this line below
                //[System.ComponentModel.DefaultValueAttribute(false)]                
		public bool ignorePresentationIndicators = false;                .
                .
        }


     II. When using wsdl.exe that comes with .NET 1.0, it was found that some of the tags like fkcallingsearchspace_autoregistration were not getting updated to null/none in the database. 
	 On furthur analysis it looked to be an issue with .NET not serializing tags which was defined as nillable=true in the schema. 
	 An example to workaround for a tag callingSearchSpace in updatePhoneReq in the generated stub is to remove the "Form=System.Xml.Schema.XmlSchemaForm.Unqualified" from 
	
	 [System.Xml.Serialization.XmlElementAttribute("name", typeof(string), Form=System.Xml.Schema.XmlSchemaForm.Unqualified)]
         [System.Xml.Serialization.XmlElementAttribute("uuid", typeof(string), Form=System.Xml.Schema.XmlSchemaForm.Unqualified)]
         [System.Xml.Serialization.XmlChoiceIdentifierAttribute("ItemElementName")]
         public string Item;

	 This way the tags will be serialized by the serializer. So now passing the tag value as "" will set the callingSearchSpace to null/None. Same is applicable to other such tags.

    III. When using wsdl.exe that comes with .NET 1.0, it was found that if we try to add elements like gatewayEndpoint,MGCPEndpoint or CSS where name contained some special character 
	 then it did not get updated in the database properly.
	 For example gatewayEndpoint with name="AALN@SAA000011114444" was being sent as name="AALN_x0040_SAA000011114444" in the AXL request.
 
	 On further analysis it looked to be a limitation with .NET serialization of tags which are defined as type xsd:Name in the schema.
 
	 In XML specification the type xsd:name is defined as a token beginning with a letter or one of a few punctuation characters, and continuing with letters, digits,hyphens, underscores, 
	 colons, or full stops, together known as name characters. Thus xsd:name does not allow any of the special characters like '@' or '/', etc.
 
	 A workaround for this would be to change the  data  type from "Name" to "string" in the generated stub:	

	Before:
		public class XDevice {
        	  [System.Xml.Serialization.XmlElementAttribute(Form=System.Xml.Schema.XmlSchemaForm.Unqualified, DataType="Name")]
		  public string name;
	After:
 		public class XDevice {
		   [System.Xml.Serialization.XmlElementAttribute(typeof(string), Form=System.Xml.Schema.XmlSchemaForm.Unqualified)]
		   public string name;
 
	This way the special characters in the name will be updated in the database without any conversion.



NOTE: By default, the AXLAPI.wsdl has the namespace http://www.cisco.com/AXL/API/7.0. The client stubs generated will also have this namespace.
      But the namespace returned in AXL response will depend on two things:
	a) Whether the SOAPAction attribute in the HTTP header had "CUCM:DB ver=7.0".

	b) Service Parameter "Send Valid Namespace in AXL Response" setting from CCMAdmin Service Parameter page.

      If point a above is true, the AXL response will either have namespace http://www.cisco.com/AXL/API/7.0 or http://www.cisco.com/AXL/7.0 depending on the value of point b above.
      When the Service Parameter "Send Valid Namespace in AXL Response" in point b above is set to true, the namespace returned in AXL response will be http://www.cisco.com/AXL/API/7.0 which will match with the namespace from the AXLAPI.wsdl.
      In case the Service Parameter above is set to False, the namespace returned in AXL response will be http://www.cisco.com/AXL/7.0.
      	
      Depending on the situation, the application developer will be required to change the namespace in AXLAPI.wsdl before creating the client stubs.
