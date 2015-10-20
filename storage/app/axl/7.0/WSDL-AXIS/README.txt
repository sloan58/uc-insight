Copyright © 2006-2007 Cisco Systems, Inc.

WSDL-AXIS README File

This readme file contains information specific to the use of the AXLAPI.wsdl in Java Environment.

AXLAPI.wsdl provided in the folder WSDL-AXIS has been verified in AXIS environment.
The schema file AXLSoap.xsd is only for code generation and has been modified to enusure that future changes in the Database schema will cause minimum breakage to the code generated using WSDL and the schema files in the parent directory must be used for reference and for validation of response. 
When wsdl2Java utility is used to create the Java source code using AXLAPI.wsdl, it will throw two errors which are specific to AXIS_1_4.

For further details on these errors, please refer to http://issues.apache.org/jira/browse/AXIS-2545
and http://issues.apache.org/jira/browse/AXIS-1280

NOTE:

I. The first axis jira is because of wrong order of passing the parameters to the constructor. For example 

public class XNPDirectoryNumberShareLineAppearanceCSS  extends com.cisco.www.AXL.API._7_0.XCallingSearchSpace  implements java.io.Serializable {
>
>
super(
            uuid,
            name,
            description,
            clause,
            dialPlanWizardGenId,
            members);

But the parent constructor is defined as 
public XCallingSearchSpace(
           org.apache.axis.types.Name name,
           java.lang.String description,
           java.lang.String clause,
           org.apache.axis.types.NonNegativeInteger dialPlanWizardGenId,
           com.cisco.www.AXL.API._6_0.XCallingSearchSpaceMember[] members,
           java.lang.String uuid) {
           this.name = name;
           this.description = description;
           this.clause = clause;
           this.dialPlanWizardGenId = dialPlanWizardGenId;
           this.members = members;
           this.uuid = uuid;
    }

So we need to change either the constructor or the constructor calling as given below

super(
            name,
            description,
            clause,
            dialPlanWizardGenId,
            members,
	    uuid);

II. The second axis jira is related to having a string constructor for simple types for example:
	// Simple Types must have a String constructor
    		public XLoadInformation(java.lang.String _value) {
	        super(_value);
    	}

For such cases the corresponding schema file(axl.xsd) in the parent schema folders must be referred and implement the String class that these classes can inherit.


NOTE: By default, the AXLAPI.wsdl has the namespace http://www.cisco.com/AXL/API/7.0. The client stubs generated will also have this namespace.
      But the namespace returned in AXL response will depend on two things:
	a) Whether the SOAPAction attribute in the HTTP header had "CUCM:DB ver=7.0".

	b) Service Parameter "Send Valid Namespace in AXL Response" setting from CCMAdmin Service Parameter page.

      If point a above is true, the AXL response will either have namespace http://www.cisco.com/AXL/API/7.0 or http://www.cisco.com/AXL/7.0 depending on the value of point b above.
      When the Service Parameter "Send Valid Namespace in AXL Response" in point b above is set to true, the namespace returned in AXL response will be http://www.cisco.com/AXL/API/7.0 which will match with the namespace from the AXLAPI.wsdl.
      In case the Service Parameter above is set to False, the namespace returned in AXL response will be http://www.cisco.com/AXL/1.0.

      Depending on the situation, the application developer will be required to change the namespace in AXLAPI.wsdl before creating the client stubs.
