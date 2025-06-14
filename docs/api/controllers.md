# Controllers Documentation

This section documents the main controllers in the Angaza Referral System, their responsibilities, and key methods.

| Controller                | Responsibility                                      | Key Methods / Actions                  |
|---------------------------|----------------------------------------------------|----------------------------------------|
| MFLController             | Handles facility and health unit data from MFL API  | getFacilities, getFacility, getCommunityHealthUnits, syncFacilities, getServiceFromCategory, getFacilityFromService, getServiceCategories, getFacilityTypes, getCounties |
| ReferralController        | Manages patient referrals and related actions       | facilities, medicalTerms, submitReferral, createreferal, reviewed, counterReferral, viewReferal, viewIncomingReferal, destroy, outgoing, acceptReferralRequest, rejectReferralRequest, addReferral, storeReferral, outgoingReferralTabs, show, saveTabData, fhirJson, validateReferral, search |
| UserController            | Handles user authentication and dashboard           | signIn, login, getFacilities, select, dashboard, admin, doctor, getPatientsCount, getPhysiciansCount, getReferralsCount, logout, addPatient, addData, searchPatient, search, viewPatient |
| PatientController         | Manages patient data and related lookups            | getSubcounties, getWards, addPatient, addData, searchPatient, search, viewPatient, getPatientsCount |
| ReportController          | Generates reports for referrals                     | incomingReports, outgoingReports, completedReports |
| TriageController          | Handles triage forms and submissions                | addTriage, store |
| ExtraFormsController      | Handles extra forms (GAD7, PTSD5, etc.)            | addGad7, storeGad7, addPtsd5, storePtsd5 |
| AdminController           | Admin dashboard and charts                          | admin, testCharts |
| SmsController             | Sends SMS notifications                            | sendSms |
| Phq9Controller            | Handles PHQ-9 assessments                          | addAssessment, storeAssessment |
| ReferralTabController     | API for referral tabs                              | test, saveTab1Data, saveTab2Data, saveTab3Data, apiReferral |
| FacilityController        | Facility management                                | (various) |
| FacilityInfoController    | Facility info endpoints                            | (various) |

---

## Example: MFLController

Handles all interactions with the Master Facility List (MFL) API, including fetching facilities, facility types, service categories, and counties.

### Methods
- getFacilities: Fetch all facilities
- getFacility: Fetch a single facility
- getCommunityHealthUnits: Fetch community health units
- syncFacilities: Sync facilities with local database
- getServiceFromCategory: Get services by category
- getFacilityFromService: Get facilities by service
- getServiceCategories: List service categories
- getFacilityTypes: List facility types
- getCounties: List counties

(Repeat similar detail for other controllers as needed)

