package com.generatepdf;

import java.util.HashMap;

import net.sf.jasperreports.engine.JREmptyDataSource;
import net.sf.jasperreports.engine.JasperExportManager;
import net.sf.jasperreports.engine.JasperFillManager;
import net.sf.jasperreports.engine.JasperPrint;

public class CompileReport {
    public static void main(String[] args) {
        if (args.length < 2) {
            System.err.println("Usage: java CompileReport <path_to_jasper_file> <output_pdf_file>");
            return;
        }

        try {
            // Path ke file .jasper dari argumen
            // String jasperFile = args[0];
            String jasperFile = "C:\\xampp\\htdocs\\dashboard-rs-paru\\storage\\app\\public\\jasper\\report10.jasper";
            // Path untuk output file PDF
            String outputFile = "C:\\xampp\\htdocs\\dashboard-rs-paru\\storage\\app\\public\\pdf\\report10.pdf";



            // Mengisi laporan dengan data (misalnya dengan parameter kosong)
            JasperPrint jasperPrint = JasperFillManager.fillReport(jasperFile, new HashMap<>(), new JREmptyDataSource());

            // Menyimpan laporan ke PDF
            JasperExportManager.exportReportToPdfFile(jasperPrint, outputFile);

            System.out.println("Laporan berhasil dibuat: " + outputFile);
            System.out.println("Received Jasper file path: " + jasperFile);
            System.out.println("Jasper file path: " + jasperFile);
            System.out.println("Output PDF path: " + outputFile);


        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}