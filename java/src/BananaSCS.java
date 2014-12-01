import java.awt.EventQueue;

import javax.swing.JFrame;
import com.jgoodies.forms.layout.FormLayout;
import com.jgoodies.forms.layout.ColumnSpec;
import com.jgoodies.forms.layout.RowSpec;
import com.jgoodies.forms.factories.FormFactory;
import javax.swing.JLabel;
import javax.swing.JTextField;
import javax.swing.JButton;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.Color;
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
	
	private String secret = "87442006059419051929708";

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

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frmBananascs = new JFrame();
		frmBananascs.setBackground(new Color(139, 0, 0));
		frmBananascs.getContentPane().setBackground(new Color(160, 82, 45));
		frmBananascs.setTitle("BananaSCS");
		//frmBananascs.setIconImage(null);
		frmBananascs.setBounds(100, 100, 450, 300);
		frmBananascs.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frmBananascs.getContentPane().setLayout(new FormLayout(new ColumnSpec[] {
				FormFactory.RELATED_GAP_COLSPEC,
				FormFactory.DEFAULT_COLSPEC,
				FormFactory.RELATED_GAP_COLSPEC,
				ColumnSpec.decode("default:grow"),},
			new RowSpec[] {
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,
				FormFactory.RELATED_GAP_ROWSPEC,
				FormFactory.DEFAULT_ROWSPEC,}));
		
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
		
		JLabel ltanLabel = new JLabel("Your Generated TAN");
		ltanLabel.setForeground(new Color(255, 255, 0));
		frmBananascs.getContentPane().add(ltanLabel, "4, 7");
		
		tanField = new JTextField();
		tanField.setEditable(false);
		frmBananascs.getContentPane().add(tanField, "4, 8, fill, default");
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
				//System.out.println(pin+amount+dest);
				
				MessageDigest messageDigest;
				try {
					messageDigest = MessageDigest.getInstance("SHA-1");
				
				messageDigest.update((pin).getBytes());
				messageDigest.update((pin+amount+dest+secret).getBytes());
				String encryptedString = new String(messageDigest.digest());
				encryptedString = encryptedString.replaceAll("\\D+","");
				BigInteger hash = new BigInteger(1, messageDigest.digest());
		        String result = hash.toString(16);
		        result = result.replaceAll("\\D+","");
		        //Random random = new Random(Long.parseLong(secret));
		        //int randInt = random.nextInt((1000 - 0) + 1) + 0;
				//System.out.println(result);
				tanField.setText(result);
				} catch (NoSuchAlgorithmException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				
			}
		});
		frmBananascs.getContentPane().add(tanButton, "4, 10");
	}

}
