# Models Documentation

This section documents the main Eloquent models in the Angaza Referral System and their relationships.

| Model                   | Description                                 | Key Relationships                |
|-------------------------|---------------------------------------------|-----------------------------------|
| CommunityHealthUnit     | Community health unit entity                | HasMany: CommunityHealthWorker    |
| CommunityHealthWorker   | Community health worker entity              | BelongsTo: CommunityHealthUnit    |
| Facility                | Health facility entity                      | HasMany: FacilityAddress, FacilityContact, FacilityService, Referral |
| FacilityAddress         | Address for a facility                      | BelongsTo: Facility               |
| FacilityContact         | Contact info for a facility                 | BelongsTo: Facility               |
| FacilityService         | Services offered by a facility              | BelongsTo: Facility, Service      |
| SyncLog                 | Log of sync operations                      | -                                 |
| Patient                 | Patient entity                              | HasMany: Referral                 |
| Referral                | Referral record                             | BelongsTo: Patient, Facility      |
| User                    | System user                                 | HasMany: Referral, BelongsToMany: Facility |
| Service                 | Service entity                              | HasMany: FacilityService          |
| ServiceCategory         | Service category                            | HasMany: Service                  |
| Subcounty, County       | Administrative regions                      | HasMany: Facility                 |
| Phq9Assessment, Gad7, Ptsd5, Dast10 | Assessment models                | BelongsTo: Patient                |

---

## Example: Facility Model

Represents a health facility. Related to FacilityAddress, FacilityContact, FacilityService, and Referral models.

### Relationships
- HasMany: FacilityAddress
- HasMany: FacilityContact
- HasMany: FacilityService
- HasMany: Referral

(Repeat similar detail for other models as needed)

## CommunityHealthUnit


### Model Relations

## CommunityHealthWorker


### Model Relations

## Facility


### Model Relations

## FacilityAddress


### Model Relations

## FacilityContact


### Model Relations

## FacilityService


### Model Relations

## SyncLog


### Model Relations

