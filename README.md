# Shift SDK PHP

## Installation

    composer install bringup-minabe/shift-sdk-php

## Usage examples

    try {
        $ShiftSdkPhp = new ShiftSdkPhp('http://localhost');
        $ShiftSdkPhp->createExternalAppToken();
    }

    // Exceptions
    // @throws UnauthorizedException
    // @throws NotFoundException
    // @throws InternalServerErrorException
    // @throws Exception