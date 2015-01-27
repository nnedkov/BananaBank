import java.awt.EventQueue;

import javax.swing.JFrame;
import com.jgoodies.forms.layout.FormLayout;
import com.jgoodies.forms.layout.ColumnSpec;
import com.jgoodies.forms.layout.RowSpec;
import com.jgoodies.forms.factories.FormFactory;

import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JTextField;
import javax.swing.JButton;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.Color;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Random;


public class BananaSCS {

	private JFrame frmBananascs;
	private JTextField pinField;
	private JTextField destField;
	private JTextField amountField;

	private JTextField tanField;

	private JTextField descriptionField;

	private JButton uploadButton;
	private JLabel downloadLabel;
	
	private String textFromFile;

	private boolean setFile = false;
	private JLabel fileLabel;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					BananaSCS window = new BananaSCS();
					window.frmBananascs.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the application.
	 */
	public BananaSCS() {
		initialize();
	}
	
	public static String deserializeString(File file)
			  throws IOException {
			      int len;
			      char[] chr = new char[4096];
			      final StringBuffer buffer = new StringBuffer();
			      final FileReader reader = new FileReader(file);
			      try {
			          while ((len = reader.read(chr)) > 0) {
			              buffer.append(chr, 0, len);
			          }
			      } finally {
			          reader.close();
			      }
			      return buffer.toString();
			  }
	
	private void hashTan (String data){
		MessageDigest messageDigest;
		try {
			messageDigest = MessageDigest.getInstance("SHA-1");
			long time = System.currentTimeMillis() / 100000;
			messageDigest.update((data+secret+time).getBytes());
			BigInteger hash = new BigInteger(1, messageDigest.digest());
			String result = hash.toString(16).substring(0, 20);
			tanField.setText(result);
			} catch (NoSuchAlgorithmException e1) {
				// 	TODO Auto-generated catch block
				e1.printStackTrace();
			}
	}
	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {

        frmBananascs = new JFrame();

        frmBananascs.setBackground(new Color(139, 0, 0));

        frmBananascs.getContentPane().setBackground(new Color(160, 82, 45));

        frmBananascs.setTitle("BananaSCS");

        // frmBananascs.setIconImage(null);

        frmBananascs.setBounds(100, 100, 450, 300);

        frmBananascs.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        frmBananascs.getContentPane().setLayout(
                        new FormLayout(new ColumnSpec[] {

                        FormFactory.RELATED_GAP_COLSPEC,

                        FormFactory.DEFAULT_COLSPEC,

                        FormFactory.RELATED_GAP_COLSPEC,

                        ColumnSpec.decode("default:grow"),

                        FormFactory.DEFAULT_COLSPEC, },

                        new RowSpec[] {

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,
                        
                        FormFactory.RELATED_GAP_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.RELATED_GAP_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.RELATED_GAP_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC,

                        FormFactory.RELATED_GAP_ROWSPEC,

                        FormFactory.DEFAULT_ROWSPEC, }));

        JLabel pinLabel = new JLabel("Your PIN");

        pinLabel.setForeground(new Color(255, 255, 0));

        frmBananascs.getContentPane().add(pinLabel, "4, 1");

        pinField = new JTextField();

        frmBananascs.getContentPane().add(pinField, "4, 2, fill, default");

        pinField.setColumns(10);

        JLabel destLabel = new JLabel("Destination");

        destLabel.setForeground(new Color(255, 255, 0));

        frmBananascs.getContentPane().add(destLabel, "4, 3");

        destField = new JTextField();

        frmBananascs.getContentPane().add(destField, "4, 4, fill, default");

        destField.setColumns(10);

        JLabel amountLabel = new JLabel("Amount");

        amountLabel.setForeground(new Color(255, 255, 0));

        frmBananascs.getContentPane().add(amountLabel, "4, 5");

        amountField = new JTextField();

        frmBananascs.getContentPane().add(amountField, "4, 6, fill, default");

        amountField.setColumns(10);
        
        JLabel descriptionLabel = new JLabel("Description");

        descriptionLabel.setForeground(new Color(255, 255, 0));

        frmBananascs.getContentPane().add(descriptionLabel, "4, 7");

        descriptionField = new JTextField();

        frmBananascs.getContentPane().add(descriptionField, "4, 8, fill, default");

        descriptionField.setColumns(120);

        JLabel ltanLabel = new JLabel("Your Generated TAN");

        ltanLabel.setForeground(new Color(255, 255, 0));

        frmBananascs.getContentPane().add(ltanLabel, "4, 9");

        tanField = new JTextField();

        tanField.setEditable(false);

        frmBananascs.getContentPane().add(tanField, "4, 10, fill, default");

        tanField.setColumns(10);

        JButton tanButton = new JButton("Generate TAN");

        tanButton.setForeground(new Color(255, 255, 0));

        tanButton.setBackground(new Color(128, 0, 0));

        tanButton.addMouseListener(new MouseAdapter() {
			@Override
			public void mouseClicked(MouseEvent e) {
				//TODO
				String pin = pinField.getText();
				String amount = amountField.getText();
				String dest = destField.getText();
				String description = descriptionField.getText();
				if(pin.equals("") || pin.equals("Enter the PIN!")){
					pinField.setText("Enter the PIN!");
					return;
				}
				if(dest.equals("") || dest.equals("Enter the destination!")){
					if(!setFile){
						destField.setText("Enter the destination!");
						return;
					}
				}
				if(amount.equals("") || amount.equals("Enter the amount!")){
					if(!setFile){
						amountField.setText("Enter the amount!");
						return;
					}
				}
				
				if (setFile){
					hashTan(pin+textFromFile);
				}else{
					 hashTan(pin + amount + dest + description);
				}
			}
		});
		
		downloadLabel = new JLabel("Or upload a batch transaction File");
		downloadLabel.setForeground(new Color(255, 255, 0));
		frmBananascs.getContentPane().add(downloadLabel, "4, 10");
		
		uploadButton = new JButton("Upload Text File");
		uploadButton.setBackground(new Color(128, 0, 0));
		uploadButton.setForeground(Color.YELLOW);
		frmBananascs.getContentPane().add(uploadButton, "4, 12");
		
		fileLabel = new JLabel("");
		fileLabel.setForeground(Color.YELLOW);
		frmBananascs.getContentPane().add(fileLabel, "4, 14");
		frmBananascs.getContentPane().add(tanButton, "4, 16");
		
		uploadButton.addMouseListener(new MouseAdapter() {
			@Override
			public void mouseClicked(MouseEvent e) {
				//TODO
				// JFileChooser
		        JFileChooser chooser = new JFileChooser();
		        // Dialog 
		        int var = chooser.showOpenDialog(null);
		        
		        
		        if(var == JFileChooser.APPROVE_OPTION)
		        {
		            setFile=true;
		        	fileLabel.setText(chooser.getSelectedFile().getName());
		        	try {
						String tempText = deserializeString(chooser.getSelectedFile());
						textFromFile = tempText.replaceAll("(?m)^[ \t]*\r?\n", "");
						
					} catch (IOException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
				}
				
			}
		});
	}

}
