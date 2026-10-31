# Project 1 test_curl file
#!/usr/bin/env bash
# Referenced GitHub ase230/module1/code/7_Security/Basics/bearer/test_curl.sh


set -u
 
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'
 
BASE_URL="http://localhost:8080"
TOKEN="admin"
PASS_COUNT=0
FAIL_COUNT=0
 
# Runs a curl request, then checks both the HTTP status and a substring
# in the response body. Prints PASS/FAIL instead of leaving it to the reader.
run_test() {
    local description="$1"
    local expected_status="$2"
    local expected_substring="$3"
    shift 3
 
    local response
    response=$(curl -s -w "\n%{http_code}" "$@")
    local status="${response##*$'\n'}"
    local body="${response%$'\n'*}"
 
    echo -e "${BLUE}${description}${NC}"
    echo "  Response: $body"
    echo "  Status:   $status"
 
    if [[ "$status" == "$expected_status" && "$body" == *"$expected_substring"* ]]; then
        echo -e "  ${GREEN}PASS${NC}"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        echo -e "  ${RED}FAIL${NC} (expected status $expected_status, body containing \"$expected_substring\")"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    echo ""
}
 
echo "Tests"
echo "=============================================="
echo ""
  
echo -e "${BLUE}Get all students${NC}"
run_test "  GET /students" \
    200 '"success":true' \
    "$BASE_URL/students"

echo -e "${BLUE}Get all courses${NC}"
run_test "  GET /courses" \
    200 '"success":true' \
    "$BASE_URL/courses"
 

echo -e "${BLUE}Create a new student${NC}"
STUDENT_RESPONSE=$(curl -s -X POST "$BASE_URL/students" \
     -H "Content-Type: application/json" \
     -d "{\"name\":\"Test Student\",\"email\":\"test@example.com\",\"major\":\"Cybersecurity\",\"year\":1}")
echo "  Response: $STUDENT_RESPONSE"
 
STUDENT_ID=$(echo "$STUDENT_RESPONSE" | grep -o '"id": *[0-9]*' | grep -o '[0-9]*')
 
if [[ -n "$STUDENT_ID" ]]; then
    echo -e "  ${GREEN}PASS${NC} (captured student id: $STUDENT_ID)"
    PASS_COUNT=$((PASS_COUNT + 1))
else
    echo -e "  ${RED}FAIL${NC} (no id in POST /students response)"
    FAIL_COUNT=$((FAIL_COUNT + 1))
fi
echo ""
 

echo -e "${BLUE}Create a new course${NC}"
COURSE_RESPONSE=$(curl -s -X POST "$BASE_URL/courses" \
     -H "Content-Type: application/json" \
     -d '{"title":"Test Course","department":"Cybersecurity","credits":3}')
echo "  Response: $COURSE_RESPONSE"
 
COURSE_ID=$(echo "$COURSE_RESPONSE" | grep -o '"id": *[0-9]*' | grep -o '[0-9]*')
 
if [[ -n "$COURSE_ID" ]]; then
    echo -e "  ${GREEN}PASS${NC} (captured course id: $COURSE_ID)"
    PASS_COUNT=$((PASS_COUNT + 1))
else
    echo -e "  ${RED}FAIL${NC} (no id in POST /courses response)"
    FAIL_COUNT=$((FAIL_COUNT + 1))
fi
echo ""
 

echo -e "${BLUE} Get student by ID${NC}"
run_test "  GET /students/$STUDENT_ID" \
    200 '"name":"Test Student"' \
    "$BASE_URL/students/$STUDENT_ID"
 

echo -e "${BLUE}Get course by ID${NC}"
run_test "  GET /courses/$COURSE_ID" \
    200 '"title":"Test Course"' \
    "$BASE_URL/courses/$COURSE_ID"


echo -e "${BLUE}Update student${NC}"
run_test "  PUT /students (id $STUDENT_ID)" \
    200 "Student updated successfully" \
    -X PUT "$BASE_URL/students" -H "Content-Type: application/json" \
    -d "{\"id\":${STUDENT_ID},\"name\":\"Test Student Updated\",\"email\":\"test${STAMP}@example.com\",\"major\":\"Cybersecurity\",\"year\":2}"
 

echo -e "${BLUE}Update course${NC}"
run_test "  PUT /courses (id $COURSE_ID)" \
    200 "Course updated successfully" \
    -X PUT "$BASE_URL/courses" -H "Content-Type: application/json" \
    -d "{\"id\":${COURSE_ID},\"title\":\"Test Course Updated\",\"department\":\"Computer Science\",\"credits\":4}"
 

echo -e "${BLUE}Delete student by ID (Bearer token required)${NC}"
run_test "  Invalid token" \
    401 "Invalid or expired token" \
    -X DELETE -H "Authorization: Bearer not-a-real-token" "$BASE_URL/students/$STUDENT_ID"
 
run_test "  Missing token" \
    401 "Bearer token required" \
    -X DELETE "$BASE_URL/students/$STUDENT_ID"
 
run_test "  Valid token" \
    200 "Student deleted successfully" \
    -X DELETE -H "Authorization: Bearer $TOKEN" "$BASE_URL/students/$STUDENT_ID"
 

echo -e "${BLUE}Step 10: Delete course by ID (Bearer token required)${NC}"
run_test "  Invalid token" \
    401 "Invalid or expired token" \
    -X DELETE -H "Authorization: Bearer not-a-real-token" "$BASE_URL/courses/$COURSE_ID"
 
run_test "  Missing token" \
    401 "Bearer token required" \
    -X DELETE "$BASE_URL/courses/$COURSE_ID"
 
run_test "  Valid token" \
    200 "Course deleted successfully" \
    -X DELETE -H "Authorization: Bearer $TOKEN" "$BASE_URL/courses/$COURSE_ID"
 
echo "=============================================="
echo -e "Results: ${GREEN}${PASS_COUNT} passed${NC}, ${RED}${FAIL_COUNT} failed${NC}"
echo ""
 
echo "Valid token:"
echo "- admin-token / admin"
echo "- ase230-project1-token' => 'ase230"
 
if [[ $FAIL_COUNT -gt 0 ]]; then
    exit 1
fi
 