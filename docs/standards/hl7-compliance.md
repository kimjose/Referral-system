# HL7 Compliance

## Overview
The Angaza Referral System implements HL7 (Health Level 7) standards for healthcare data exchange and interoperability.

## HL7 Standards Implemented
- HL7 v2.x messaging
- HL7 v3 CDA (Clinical Document Architecture)
- HL7 FHIR (Fast Healthcare Interoperability Resources)

## Message Types
- ADT (Admission, Discharge, Transfer)
- ORU (Observation Result)
- ORM (Order Message)
- SIU (Scheduling Information)

## Implementation Details
- Message validation
- Error handling
- Acknowledgment processing
- Message routing
- Security measures

## Compliance Requirements
- Message structure compliance
- Data type validation
- Segment requirements
- Field requirements
- Value set validation

## Testing
- Message validation tests
- Integration tests
- Performance tests
- Error handling tests

## References
- [HL7 Standards](https://www.hl7.org/implement/standards/)
- [HL7 Implementation Guide](https://www.hl7.org/implement/standards/)

## 1. HL7 Message Types

### 1.1 ADT (Admission, Discharge, Transfer)
```hl7
# ADT^A01 - Patient Admission
MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315120000||ADT^A01|MSG00001|P|2.5.1||
EVN|A01|20240315120000||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
PV1|1|I|GENERAL^101|||||ADMITTING^JANE^MD|||||||ADM|A||

# ADT^A02 - Patient Transfer
MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315130000||ADT^A02|MSG00002|P|2.5.1||
EVN|A02|20240315130000||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
PV1|1|I|CARDIOLOGY^201|||||ATTENDING^JANE^MD|||||||ADM|A||
```

### 1.2 ORU (Observation Result)
```hl7
# ORU^R01 - Lab Results
MSH|^~\&|LIS|LAB|HIS|HOSPITAL|20240315140000||ORU^R01|MSG00003|P|2.5.1||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
OBR|1|LAB12345|LAB12345|CBC^Complete Blood Count|R||20240315140000|||||||F||||||
OBX|1|NM|WBC^White Blood Cells|1|7.5|10^9/L|4.5-11.0|N|||F|||20240315140000|
OBX|2|NM|RBC^Red Blood Cells|1|5.0|10^12/L|4.5-5.5|N|||F|||20240315140000|
```

### 1.3 SIU (Scheduling Information)
```hl7
# SIU^S12 - New Appointment
MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315150000||SIU^S12|MSG00004|P|2.5.1||
SCH|1|APPT12345|APPT12345|CONSULT^Consultation|R||20240320100000|20240320110000||||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
RGS|1|1|A
AIL|1|1|CARDIOLOGY^201|A
```

## 2. Message Structure

### 2.1 Required Segments
```hl7
# Example of Required Segments in ADT Message
MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315120000||ADT^A01|MSG00001|P|2.5.1||
EVN|A01|20240315120000||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
PV1|1|I|GENERAL^101|||||ADMITTING^JANE^MD|||||||ADM|A||
```

### 2.2 Optional Segments
```hl7
# Example of Optional Segments in ADT Message
MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315120000||ADT^A01|MSG00001|P|2.5.1||
EVN|A01|20240315120000||
PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
NK1|1|SMITH^MARY^WIFE|WIFE|123 MAIN ST^^NAIROBI^KE^00100|254712345678|
AL1|1|DA|PENICILLIN|SEVERE ALLERGIC REACTION|
DG1|1|I10|I10^Essential hypertension|20240315120000|A|
PV1|1|I|GENERAL^101|||||ADMITTING^JANE^MD|||||||ADM|A||
IN1|1|INSURANCE1|MEDICAL INSURANCE|123456789||20240101|20241231||
```

## 3. Implementation Requirements

### 3.1 Message Format
```python
# Example message formatting function
def format_hl7_message(message_type, segments):
    msh = create_msh_segment(message_type)
    formatted_message = msh + "\n"
    
    for segment in segments:
        formatted_message += segment + "\n"
    
    return formatted_message

# Example usage
message = format_hl7_message("ADT^A01", [
    "EVN|A01|20240315120000||",
    "PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||",
    "PV1|1|I|GENERAL^101|||||ADMITTING^JANE^MD|||||||ADM|A||"
])
```

### 3.2 Message Processing
```python
# Example message processing function
def process_hl7_message(message):
    try:
        # Validate message structure
        validate_message_structure(message)
        
        # Parse segments
        segments = parse_segments(message)
        
        # Process based on message type
        message_type = get_message_type(segments)
        if message_type == "ADT^A01":
            process_admission(segments)
        elif message_type == "ORU^R01":
            process_lab_results(segments)
            
        # Generate acknowledgment
        return generate_ack(segments, "AA")
    except Exception as e:
        return generate_ack(segments, "AE", str(e))
```

### 3.3 Security Requirements
```python
# Example message encryption
def encrypt_hl7_message(message):
    # Generate encryption key
    key = generate_encryption_key()
    
    # Encrypt message
    encrypted_message = encrypt_with_aes(message, key)
    
    # Add security headers
    security_headers = create_security_headers(key)
    
    return security_headers + encrypted_message

# Example message signing
def sign_hl7_message(message):
    # Generate digital signature
    signature = generate_digital_signature(message)
    
    # Add signature to message
    return message + "\nSIG|" + signature
```

## 4. Integration Guidelines

### 4.1 Interface Requirements
```python
# Example MLLP wrapper
class MLLPWrapper:
    def __init__(self, host, port):
        self.host = host
        self.port = port
        self.socket = None
        
    def connect(self):
        self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.socket.connect((self.host, self.port))
        
    def send_message(self, message):
        # Add MLLP wrappers
        wrapped_message = f"\x0B{message}\x1C\x0D"
        self.socket.send(wrapped_message.encode())
        
    def receive_message(self):
        data = self.socket.recv(4096)
        # Remove MLLP wrappers
        return data.decode().strip('\x0B\x1C\x0D')
```

### 4.2 Data Mapping
```python
# Example data mapping function
def map_patient_data(patient_data):
    return {
        "PID": {
            "1": "1",  # Set ID
            "3": patient_data["medical_record_number"],
            "5": f"{patient_data['last_name']}^{patient_data['first_name']}",
            "7": patient_data["date_of_birth"],
            "8": patient_data["gender"],
            "11": format_address(patient_data["address"])
        }
    }

# Example usage
patient_data = {
    "medical_record_number": "12345",
    "last_name": "SMITH",
    "first_name": "JOHN",
    "date_of_birth": "19741225",
    "gender": "M",
    "address": {
        "street": "123 MAIN ST",
        "city": "NAIROBI",
        "country": "KE",
        "postal_code": "00100"
    }
}
mapped_data = map_patient_data(patient_data)
```

## 5. Testing Requirements

### 5.1 Message Testing
```python
# Example message validation test
def test_message_validation():
    # Test valid message
    valid_message = """
    MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315120000||ADT^A01|MSG00001|P|2.5.1||
    PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100||
    """
    assert validate_message(valid_message) == True
    
    # Test invalid message
    invalid_message = """
    MSH|^~\&|HIS|HOSPITAL|LIS|LAB|20240315120000||ADT^A01|MSG00001|P|2.5.1||
    PID|1|12345|||SMITH^JOHN^^^^||19741225|M|||123 MAIN ST^^NAIROBI^KE^00100
    """
    assert validate_message(invalid_message) == False
```

### 5.2 Integration Testing
```python
# Example integration test
def test_hl7_interface():
    # Create test message
    test_message = create_test_message()
    
    # Send message
    response = send_hl7_message(test_message)
    
    # Verify response
    assert response.status_code == 200
    assert validate_ack(response.message) == True
    
    # Verify data in database
    assert verify_patient_data(test_message) == True
```

## 6. Maintenance and Support

### 6.1 Version Management
```yaml
# Example version configuration
hl7_version: 2.5.1
message_types:
  - name: ADT
    version: 2.5.1
    status: active
  - name: ORU
    version: 2.5.1
    status: active
  - name: SIU
    version: 2.5.1
    status: active
```

### 6.2 Documentation
```markdown
# HL7 Interface Documentation

## Message Types
- ADT^A01: Patient Admission
- ADT^A02: Patient Transfer
- ORU^R01: Lab Results
- SIU^S12: New Appointment

## Required Segments
- MSH: Message Header
- PID: Patient Identification
- PV1: Patient Visit

## Optional Segments
- NK1: Next of Kin
- AL1: Allergy
- DG1: Diagnosis
```

## 7. Compliance Monitoring

### 7.1 Performance Monitoring
```python
# Example performance metrics
performance_metrics = {
    "message_processing": {
        "total_messages": 1000,
        "processing_time": {
            "average": 150,  # ms
            "p95": 300,      # ms
            "p99": 500       # ms
        },
        "error_rate": 0.01
    },
    "interface_health": {
        "uptime": 0.999,
        "response_time": 100,  # ms
        "connection_errors": 5
    }
}
```

### 7.2 Quality Monitoring
```python
# Example quality metrics
quality_metrics = {
    "message_quality": {
        "validity": 0.99,
        "completeness": 0.98,
        "accuracy": 0.99
    },
    "data_quality": {
        "mapping_accuracy": 0.99,
        "value_set_compliance": 0.98,
        "required_field_completion": 0.99
    }
}
``` 