# Shift SDK PHP

## Usage examples

    try {
        $ShiftSdkPhp = new ShiftSdkPhp(
            'http://localhost',
            'key',
            'secret'
        );
        $ShiftSdkPhp->createToken();
    }

    // @throws UnauthorizedException
    // @throws NotFoundException
    // @throws Exception