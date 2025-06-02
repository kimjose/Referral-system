# System Diagrams

This section provides visual representations of the Angaza Referral System's architecture, processes, and data flows.

## Process Flow Diagrams

### Authentication Flow
```mermaid
graph TD
    A[User Access] --> B{Has Account?}
    B -->|No| C[Register]
    B -->|Yes| D[Login]
    C --> E[Enter Details]
    E --> F[Verify Email]
    F --> G[Set Password]
    G --> H[Login]
    D --> I{Valid Credentials?}
    I -->|No| D
    I -->|Yes| J[Access Dashboard]
    J --> K[Role-based Access]
```

### Referral Process Flow
```mermaid
graph TD
    A[Login] --> B[Access Dashboard]
    B --> C[Select Patient]
    C --> D[Enter Referral Details]
    D --> E[Select Receiving Facility]
    E --> F[Add Clinical Notes]
    F --> G[Attach Documents]
    G --> H[Review Referral]
    H --> I{Approved?}
    I -->|No| D
    I -->|Yes| J[Send Referral]
    J --> K[Track Status]
    K --> L[Receive Response]
```

### Patient Management Flow
```mermaid
graph TD
    A[Login] --> B[Patient Module]
    B --> C{New Patient?}
    C -->|Yes| D[Register Patient]
    C -->|No| E[Search Patient]
    D --> F[Enter Demographics]
    F --> G[Add Medical History]
    G --> H[Save Record]
    E --> I[View Patient Details]
    I --> J[Update Information]
    J --> K[Add Visit Notes]
    K --> L[Generate Reports]
```

### Facility Management Flow
```mermaid
graph TD
    A[Login] --> B[Facility Module]
    B --> C{New Facility?}
    C -->|Yes| D[Register Facility]
    C -->|No| E[Search Facility]
    D --> F[Enter Facility Details]
    F --> G[Add Services]
    G --> H[Set Capacity]
    H --> I[Assign Staff]
    E --> J[View Facility Details]
    J --> K[Update Information]
    K --> L[Manage Resources]
```

### Reporting Flow
```mermaid
graph TD
    A[Login] --> B[Reports Module]
    B --> C[Select Report Type]
    C --> D[Set Parameters]
    D --> E[Generate Report]
    E --> F{Format?}
    F -->|PDF| G[Download PDF]
    F -->|Excel| H[Download Excel]
    F -->|Print| I[Print Report]
    G --> J[Share Report]
    H --> J
    I --> J
```

### User Management Flow
```mermaid
graph TD
    A[Admin Login] --> B[User Management]
    B --> C{Action?}
    C -->|Create| D[Add New User]
    C -->|Edit| E[Modify User]
    C -->|Delete| F[Remove User]
    D --> G[Assign Role]
    E --> H[Update Permissions]
    F --> I[Confirm Deletion]
    G --> J[Save Changes]
    H --> J
    I --> J
```

### Notification Flow
```mermaid
graph TD
    A[System Event] --> B{Event Type?}
    B -->|Referral| C[Send to Receiving Facility]
    B -->|Status Update| D[Notify Sending Facility]
    B -->|New Message| E[Alert Users]
    C --> F[Email Notification]
    D --> F
    E --> F
    F --> G[Update Dashboard]
    G --> H[User Action]
```

### Data Synchronization Flow
```mermaid
graph TD
    A[Data Change] --> B{Change Type?}
    B -->|Patient| C[Update NHDD]
    B -->|Facility| D[Update KMFL]
    B -->|Clinical| E[Update FHIR]
    C --> F[Verify Sync]
    D --> F
    E --> F
    F --> G{Success?}
    G -->|No| H[Retry Sync]
    G -->|Yes| I[Log Success]
```

## Module Interactions

### Cross-Module Communication
```mermaid
graph TD
    A[Patient Module] --> B[Referral Module]
    A --> C[Reporting Module]
    B --> D[Facility Module]
    B --> E[Notification Module]
    C --> F[Data Sync Module]
    D --> G[User Module]
    E --> H[Communication Module]
    F --> I[External Systems]
```

### Data Flow Between Modules
```mermaid
graph LR
    A[Patient Data] --> B[Referral System]
    B --> C[Facility System]
    C --> D[Notification System]
    D --> E[Reporting System]
    E --> F[Data Warehouse]
    F --> G[Analytics]
    G --> H[Dashboard]
```

## System States

### Referral Status Flow
```mermaid
stateDiagram-v2
    [*] --> Created
    Created --> Pending
    Pending --> Accepted
    Pending --> Rejected
    Accepted --> InProgress
    InProgress --> Completed
    InProgress --> Cancelled
    Completed --> [*]
    Rejected --> [*]
    Cancelled --> [*]
```

### User Session States
```mermaid
stateDiagram-v2
    [*] --> LoggedOut
    LoggedOut --> LoggedIn
    LoggedIn --> Active
    Active --> Inactive
    Inactive --> Active
    Active --> LoggedOut
    Inactive --> LoggedOut
    LoggedOut --> [*]
```

These diagrams provide a comprehensive view of the system's processes, user journeys, and module interactions. Each diagram is designed to help users and developers understand the flow of information and actions within the system.

## Usage Guidelines

1. **For Users**
   - Follow the process flows to understand system navigation
   - Use the module interaction diagrams to understand system connectivity
   - Refer to state diagrams for status tracking

2. **For Developers**
   - Use these diagrams as reference for implementation
   - Follow the data flow patterns for integration
   - Maintain consistency with the defined processes

3. **For Administrators**
   - Use the user management flow for system administration
   - Monitor the notification flow for system alerts
   - Track the data synchronization flow for system health

## Process Flow Diagram

```mermaid
flowchart TD
    A[Client Registration] --> B[Capture Medical Information]
    B --> C[Create Clinical Summary]
    C --> D[Create Referral]
    D --> E[Track Referral Status]
    E --> F[Receive Feedback]
    F --> G[Update Referral Status]
    G --> H[Complete Referral]
```

The flowchart shows the high-level process flow for the patient referral system. The process begins with client registration, and continues with the capture of medical information and clinical summary. A referral is then created, and the referral status is tracked until feedback is received.

## Use Case Diagram

```mermaid
graph TD
    subgraph Actors
        A[Client]
        B[Referring Health Worker]
        C[Referral Coordinator]
        D[Receiving Facility]
        E[Shared Health Record]
    end

    subgraph Use Cases
        U1[Register Client]
        U2[Create Referral]
        U3[Track Referral]
        U4[Update Status]
        U5[Generate Reports]
        U6[Access Medical Records]
    end

    A --> U1
    B --> U2
    B --> U3
    C --> U4
    D --> U4
    D --> U6
    E --> U6
    C --> U5
```

The use case diagram shows the different actors that interact with the patient referral system and the use cases that they can perform. The actors include:
- Client
- Referring health worker
- Referral coordinator
- Receiving facility
- Shared health record

## Activity Diagram

```mermaid
flowchart TD
    A[Start] --> B[Create New Referral]
    B --> C[Select Referral Priority]
    C --> D[Enter Diagnosis]
    D --> E[Enter Reason for Referral]
    E --> F[Select Physician/Provider]
    F --> G[Submit Referral]
    G --> H[Track Referral Status]
    H --> I{Feedback Received?}
    I -->|No| H
    I -->|Yes| J[Update Status]
    J --> K[End]
```

The activity diagram shows the detailed process flow for creating a referral in the patient referral system. The process includes:
1. Creation of a new referral
2. Selection of the referral priority
3. Entry of the diagnosis
4. Entry of the reason for referral
5. Selection of the physician/provider
6. Submission of the referral
7. Tracking of the referral status until feedback is received

## Data Flow Diagram

```mermaid
flowchart LR
    subgraph External Entities
        A[Client]
        B[Health Worker]
        C[Facility]
    end

    subgraph Processes
        P1[Client Registration]
        P2[Medical Information]
        P3[Referral Management]
        P4[Status Tracking]
    end

    subgraph Data Stores
        D1[(Client Database)]
        D2[(Medical Records)]
        D3[(Referral Database)]
        D4[(Feedback Database)]
    end

    subgraph Reports
        R1[System Reports]
        R2[Analytics]
    end

    A --> P1
    B --> P2
    B --> P3
    C --> P4

    P1 --> D1
    P2 --> D2
    P3 --> D3
    P4 --> D4

    D1 --> R1
    D2 --> R1
    D3 --> R1
    D4 --> R1

    R1 --> R2
```

The data flow diagram shows the flow of information in the patient referral system. The system:
1. Captures information about clients, medical information, referrals, and feedback
2. Stores this information in appropriate databases
3. Uses the information to generate reports
4. Provides feedback to the referring health worker

## System Architecture

```mermaid
graph TD
    subgraph Frontend
        F1[Web Interface]
        F2[Mobile App]
    end

    subgraph Backend
        B1[API Layer]
        B2[Business Logic]
        B3[Data Access]
    end

    subgraph Database
        D1[(Client Data)]
        D2[(Medical Records)]
        D3[(Referral Data)]
    end

    subgraph External Systems
        E1[Shared Health Record]
        E2[Facility Management]
        E3[Reporting System]
    end

    F1 --> B1
    F2 --> B1
    B1 --> B2
    B2 --> B3
    B3 --> D1
    B3 --> D2
    B3 --> D3
    B2 --> E1
    B2 --> E2
    B2 --> E3
```

This diagram shows the high-level architecture of the Angaza Referral System, including:
- Frontend components (web interface and mobile app)
- Backend services (API layer, business logic, and data access)
- Database systems
- External system integrations 

## Visual Flowcharts

### Complete System Flow
```mermaid
flowchart TD
    subgraph User Interface
        A[Login Screen] --> B{User Type}
        B -->|Patient| C[Patient Portal]
        B -->|Provider| D[Provider Portal]
        B -->|Admin| E[Admin Portal]
    end

    subgraph Patient Journey
        C --> F[View Medical History]
        C --> G[Track Referrals]
        C --> H[View Appointments]
    end

    subgraph Provider Workflow
        D --> I[Patient Management]
        D --> J[Referral Management]
        D --> K[Document Management]
    end

    subgraph Administrative Tasks
        E --> L[User Management]
        E --> M[System Configuration]
        E --> N[Reports & Analytics]
    end

    subgraph Data Integration
        O[NHDD Integration] --> P[Data Validation]
        Q[FHIR Integration] --> R[Data Exchange]
        S[KMFL Integration] --> T[Facility Management]
    end
```

### Patient Registration Flow
```mermaid
flowchart TD
    A[Start Registration] --> B[Enter Personal Details]
    B --> C[Verify National ID]
    C --> D{ID Valid?}
    D -->|No| E[Manual Verification]
    D -->|Yes| F[Generate UPI]
    E --> F
    F --> G[Enter Medical History]
    G --> H[Upload Documents]
    H --> I[Assign Primary Facility]
    I --> J[Complete Registration]
    J --> K[Send Confirmation]
```

### Referral Processing Flow
```mermaid
flowchart TD
    A[Initiate Referral] --> B[Select Patient]
    B --> C[Enter Clinical Details]
    C --> D[Select Receiving Facility]
    D --> E[Set Priority Level]
    E --> F[Add Supporting Documents]
    F --> G[Review Referral]
    G --> H{Approved?}
    H -->|No| I[Request Changes]
    H -->|Yes| J[Send to Facility]
    I --> C
    J --> K[Track Status]
    K --> L[Receive Response]
```

### Facility Management Flow
```mermaid
flowchart TD
    A[Facility Registration] --> B[Enter Facility Details]
    B --> C[Verify KMFL Code]
    C --> D[Add Services]
    D --> E[Set Capacity]
    E --> F[Assign Staff]
    F --> G[Configure Integration]
    G --> H[Complete Setup]
    H --> I[Start Operations]
```

### Data Synchronization Flow
```mermaid
flowchart TD
    A[Data Change] --> B{Change Type}
    B -->|Patient| C[Update NHDD]
    B -->|Facility| D[Update KMFL]
    B -->|Clinical| E[Update FHIR]
    C --> F[Verify Sync]
    D --> F
    E --> F
    F --> G{Success?}
    G -->|No| H[Retry Sync]
    G -->|Yes| I[Log Success]
    H --> F
```

### Reporting System Flow
```mermaid
flowchart TD
    A[Generate Report] --> B{Report Type}
    B -->|Patient| C[Patient Reports]
    B -->|Facility| D[Facility Reports]
    B -->|System| E[System Reports]
    C --> F[Select Parameters]
    D --> F
    E --> F
    F --> G[Process Data]
    G --> H[Generate Output]
    H --> I{Format}
    I -->|PDF| J[Download PDF]
    I -->|Excel| K[Download Excel]
    I -->|Print| L[Print Report]
```

### Emergency Referral Flow
```mermaid
flowchart TD
    A[Emergency Case] --> B[Quick Registration]
    B --> C[Basic Details Only]
    C --> D[Immediate Referral]
    D --> E[High Priority Flag]
    E --> F[Direct Facility Contact]
    F --> G[Track Response]
    G --> H[Update Status]
    H --> I[Complete Documentation]
```

### Quality Assurance Flow
```mermaid
flowchart TD
    A[QA Process] --> B[Review Referral]
    B --> C{Meets Standards?}
    C -->|No| D[Request Changes]
    C -->|Yes| E[Approve]
    D --> F[Update Documentation]
    F --> B
    E --> G[Monitor Outcomes]
    G --> H[Generate QA Report]
```

### Integration Flow
```mermaid
flowchart TD
    A[External System] --> B{Integration Type}
    B -->|NHDD| C[Patient Data]
    B -->|KMFL| D[Facility Data]
    B -->|FHIR| E[Clinical Data]
    C --> F[Validate]
    D --> F
    E --> F
    F --> G{Valid?}
    G -->|No| H[Error Handling]
    G -->|Yes| I[Process Data]
    H --> J[Log Error]
    I --> K[Update System]
```

### Security Flow
```mermaid
flowchart TD
    A[Access Request] --> B{Authentication}
    B -->|Success| C[Authorization]
    B -->|Failed| D[Access Denied]
    C --> E{Has Permission?}
    E -->|Yes| F[Grant Access]
    E -->|No| D
    F --> G[Log Access]
    G --> H[Monitor Activity]
```

These flowcharts provide a comprehensive visual representation of the system's various processes and workflows. Each flowchart is designed to help users and developers understand the specific processes and their interactions within the system.

## Usage Guidelines

1. **For Users**
   - Follow the flowcharts to understand process sequences
   - Use the diagrams to identify decision points
   - Reference the flows for troubleshooting

2. **For Developers**
   - Use the flows for implementation guidance
   - Follow the process sequences for development
   - Reference the integration flows for system connections

3. **For Administrators**
   - Use the flows for process monitoring
   - Reference the security flows for access management
   - Follow the QA flows for system maintenance 