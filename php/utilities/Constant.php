<?php

class Constant
{

    // Log level
    public const ERROR_CODE = "ERROR";

    public const INFO_CODE = "INFO";

    // Message Type
    public const CALLING_MESSAGE_TYPE = "CALLING";

    public const RETURN_MESSAGE_TYPE = "RETURN";

    public const CREATE_MESSAGE_TYPE = "CREATE";

    public const DESTROY_MESSAGE_TYPE = "DESTROY";

    // Object Type
    public const ACTOR_TYPE = "ACTOR";

    public const REF_DIAGRAM_TYPE = "REF";

    public const CONCRETE_INSTANCE = "CONCRETE";

    public const ABSTRACT_INSTANCE = "ABSTRACT";

    public const INTERFACT_INSTANCE = "INTERFACE";

    public const STATIC_INSTANCE = "STATIC";
    
    public const TERMINATED_NODE = "TERMINATED";

    // Source code type
    public const STUB_TYPE = "STUB";

    public const DRIVER_TYPE = "DRIVER";

    // Source code language
    public const JAVA_LANG = "JAVA";

    public const PHP_LANG = "PHP";

    // Error message
    public const NO_CLASS_FOUND_ERROR_MSG = "Class is not found.";

    public const CLASS_NOT_UNIQUE_ERROR_MSG = "Class is not unique.";
    
    public const CODE_GENERATION_ERROR_MSG = "Internal error occured during generate source code";
    
    public const UNRELATED_CLASSES_SELECTED_ERROR_MSG = "Unrelated class under test is selected: ";
    
    public const ALL_CLASSES_SELECTED_ERROR_MSG = "All object in call graph is selected. Please revise the selection.";
    
    public const GENERIC_INTERNAL_ERROR_MSG = "Unexpected internal error occured. Please contact system administrator";

    // Primitive Data type
    public const INT_TYPE = "int";

    public const DOUBLE_TYPE = "double";

    public const FLOAT_TYPE = "float";

    public const BOOLEAN_TYPE = "boolean";

    public const LONG_TYPE = "long";

    public const SHORT_TYPE = "short";

    public const BYTE_TYPE = "byte";

    public const CHAR_TYPE = "char";

    public const STRING_TYPE = "string";

    public const VOID_TYPE = "void";

    // Data set
    public const CHAR_SET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public const CHAR_SET_LENGTH = 62;
}
