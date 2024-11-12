package com.generatepdf;

import java.io.File;

import net.sf.jasperreports.engine.JRException;
import net.sf.jasperreports.engine.JasperCompileManager;
import net.sf.jasperreports.engine.JasperReport;

public class CompileReport {
    public static void main(String[] args) {
    
    try {
        // Compile .jrxml file to .jasper
        
        // Path to your .jrxml file
        String jrxmlFile = "C:\\xampp\\htdocs\\dashboard-rs-paru\\app\\generatepdf\\src\\report\\Resum1.jrxml"; // Replace with the actual path
        // Path to output .jasper file
        String jasperFile = "C:\\xampp\\htdocs\\dashboard-rs-paru\\app\\generatepdf\\src\\report\\Resum1.jrxml"; // Replace with desired output path

        
            JasperReport jasperReport = JasperCompileManager.compileReport(jrxmlFile);
            System.out.println("Compilation complete.");
            
            // Export compiled report to .jasper file
            File outputFile = new File(jasperFile);
            JasperCompileManager.writeReportToXmlFile(jasperReport, outputFile.getPath());
            System.out.println("File saved as: " + outputFile.getPath());

        } catch (JRException e) {
            System.err.println("Error compiling report: " + e.getMessage());
            e.printStackTrace();
        }
    }
}