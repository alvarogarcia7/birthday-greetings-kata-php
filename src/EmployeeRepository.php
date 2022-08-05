<?php

namespace BirthdayGreetingsKata;

class EmployeeRepository
{

    private CSVReader $csvReader;

    public function __construct(CSVReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    public function nextEmployeeWithBirthdayOrNull(XDate $xDate): ?Employee
    {
        $employeeData = $this->csvReader->nextOrNull();
        if (!$employeeData) {
            return null;
        }
        $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
        if ($employee->isBirthday($xDate)) {
            return $employee;
        }
        return null;
    }
}